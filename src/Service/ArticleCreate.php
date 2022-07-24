<?php

namespace App\Service;

use App\Entity\Module;
use App\Form\Model\ArticleFormModel;
use App\Form\Model\ArticleImageModel;
use App\Repository\ModuleRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleCreate
{
    private Request $request;
    private FormInterface $form;
    private bool $sended;
    private ModuleRepository $moduleRepository;
    private ArticleFormModel $articleFormModel;
    private UserInterface $user;
    private array $modules;
    private int $articleSize;
    private array $imagePath;
    private FileUploader $fileUploader;
    private string $shortTemplateText;
    private string $fullText;
    private array $descriptionStructure;
    private int $pSize = 0;
    private int $tSize = 0;

    public function __construct(
        RequestStack     $requestStack,
        ModuleRepository $moduleRepository,
        FileUploader     $fileUploader,
        Security         $security
    )
    {
        $this->sended = false;

        $this->request = $requestStack->getCurrentRequest();

        $this->moduleRepository = $moduleRepository;

        $this->user = $security->getUser();

        $this->fileUploader = $fileUploader;

        $this->modules = [];

        $this->shortTemplateText = '';

    }

    public function make(FormInterface $form)
    {
        $this->setForm($form);

        $this->getData();

        $this->generateArticle();
    }

    protected function generateArticle()
    {
        if ($this->sended) {

            $this->getArticleSize();

            $this->trySaveImages();

            $this->getModules();

            $this->setArticleStructure();

            $this->makeArticle();
        }
    }

    protected function setArticleStructure()
    {
        $position = 0;

        /** @var Module $module */
        foreach ($this->modules as $module) {

            if (empty($this->descriptionStructure[$module->getId()])) {
                $this->setBlockToShortText($module->getContent());
            }

            $length = mb_strlen($module->getContent());

            $this->descriptionStructure[] = [
                'id' => $module->getId(),
                'position' => $position,
                'length' => $length
            ];

            $position += $length;
        }
    }

    /***
     * Добавляем текст модуля к шаблону, если не добавляли с таким id
     * @param string $moduleContent
     */
    protected function setBlockToShortText(string $moduleContent)
    {
        $this->shortTemplateText .= $moduleContent;
    }

    protected function makeArticle(): void
    {
        $this->parseShortTemplate();

    }

    /***
     * Parse template with content from unique modules
     * @return array
     */
    protected function parseShortTemplate(): array
    {
        preg_match_all('/{{\s+paragraphs{0,1}\s+}}/i', $this->shortTemplateText, $match, PREG_OFFSET_CAPTURE);

        $p = [];

        if (!empty($match[0])) {
            foreach ($match[0] as $piece) {
                $p[] = [
                    'position' => $piece[1],
                    'length' => mb_strlen($piece[0]),
                    'size' => mb_strpos(mb_strtolower($piece[0]), 'paragraphs') === false ? 1 : rand(1, 3)
                ];
            }
        }

        dump($this->modules);
        dump($this->descriptionStructure);
        dump($p);
        $p = $this->setCorrectSize($p);
        dump($p);

        dd($this->shortTemplateText);

        return [];
    }

    /***
     * Корректируем позиции замены тегов, исходя из дублей блоков
     * @param array $p
     */
    protected function setCorrectSize(array $p)
    {
        $n = 0;
        $last = sizeof($p);
        $checkedPosition = [];
        $new = [];

        foreach ($this->descriptionStructure as $description) {
            if (isset($checkedPosition[$description['id']])) {
                while ($n < $last) {
                    if ($p[$n]['position'] > $description['position']) {
                        $p[$n]['position'] += $description['length'];
                    }
                    $n++;
                }

            } else {
                $checkedPosition[$description['id']] = $description;
            }
        }

        return $new;
    }

    protected function trySaveImages()
    {
        if (!empty($this->articleFormModel)) {

            $images = $this->articleFormModel->images;

            if (!empty($images)) {

                $n = 1;

                /** @var ArticleImageModel[] $imageModel */
                foreach ($images as $imageModel) {

                    if ($n > $this->articleSize) {
                        break;
                    }

                    if (!empty($imageModel)) {

                        $this->imagePath[] = $this->fileUploader->uploadFile($imageModel->image);

                        $n++;
                    }
                }
            }
        }
    }

    protected function getModules()
    {
        $sizeImg = $this->getImgSize();

        if (!empty($sizeImg)) {
            $this->modules = $this->moduleRepository->findModules($sizeImg, $this->user->getId(), true);
        }

        $correctArticleSize = $this->getCorrectArticleSize();

        if ($correctArticleSize > 0) {

            $this->getSimpleModules($correctArticleSize);
        }

        if (empty($this->modules)) {
            throw new \Exception('Не найдены модули');
        }

        $this->checkArticleSize($this->articleSize);

    }


    protected function getSimpleModules($size): void
    {
        $this->modules = array_merge($this->modules, $this->moduleRepository->findModules($size, $this->user->getId(), false));
    }

    protected function getCorrectArticleSize(): int
    {
        return $this->articleSize - sizeof($this->modules);
    }

    /**
     * Добавляем модули если не удалось выбрать необходимое кол-во из БД
     */
    protected function checkArticleSize(int $size)
    {
        $curArticleSize = sizeof($this->modules);

        if ($curArticleSize < $size) {

            for ($n = 0; $n < $size - $curArticleSize; $n++) {

                $this->modules[] = $this->modules[rand(0, $curArticleSize - 1)];
            }
        }

        shuffle($this->modules);
    }

    protected function getImgSize(): int
    {
        if (!empty($this->imagePath)) {
            return sizeof($this->imagePath);
        }

        return 0;
    }

    protected function getArticleSize(): void
    {
        $this->articleSize = 1;

        $sizeFrom = (!empty($this->articleFormModel->sizeFrom) ? intVal($this->articleFormModel->sizeFrom) : 0);

        $sizeTo = (!empty($this->articleFormModel->sizeTo) ? intVal($this->articleFormModel->sizeTo) : 0);

        if ($sizeFrom && $sizeTo && $sizeFrom < $sizeTo) {
            $this->articleSize = rand($sizeFrom, $sizeTo);

        } elseif ($sizeFrom || $sizeTo) {

            $this->articleSize = !empty($sizeFrom) ? $sizeFrom : $sizeTo;
        }
    }

    protected function getData()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->sended = true;

            $this->articleFormModel = $this->form->getData();
        }
    }


    protected function setForm(FormInterface $form)
    {
        $this->form = $form;
    }
}