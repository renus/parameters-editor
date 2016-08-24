<?php

namespace Renus\ParametersEditorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\FileLocator;

/**
 * Class EditController
 * @package Renus\ParametersEditorBundle\Controller
 */
class EditController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $editor   = $this->get('renus_parameters_editor.editor');
        $editable = $editor->readConfiguration();
        $form     = $editor->buildForm($this->createFormBuilder(), $editable);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $editor->writeConfiguration($data);

            $cacheDir = $this->container->getParameter('kernel.cache_dir');
            $this->container->get('cache_clearer')->clear($cacheDir);

            $this->addFlash('success', 'Modification ok');
            return $this->redirect($this->getRequest()->headers->get('referer'));
        }

        return $this->render('RenusParametersEditorBundle:Editor:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
