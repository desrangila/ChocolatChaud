<?php

/**
 * hour actions.
 *
 * @package    chocolatchaud
 * @subpackage hour
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class hourActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->hour_list = Doctrine::getTable('Hour')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->hour = Doctrine::getTable('Hour')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->hour);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new HourForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new HourForm();
		
		$timeZone = $request->getParameter('timeZone');
		// France and Quebec
		if($timeZone == 1 || $timeZone == -5) {
			// Temps du serveur + Zone du serveur en France + Zone de l'utilisateur + heure d'été
			$time = time() + (-2 + $timeZone  + 1) * 3600;
		// Other countries
		} else {
			$time = time() + ($timeZone - 2) * 3600;
		}
		
		$time = $time - 60 * $request->getParameter('wakeup');
		$template_data = array(
			'time'=>date("H:i", $time)
		);
			
		// Send on Facebook
		
		
		// Save on the database
    $this->processForm($request, $this->form);
		
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($hour = Doctrine::getTable('Hour')->find(array($request->getParameter('id'))), sprintf('Object hour does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new HourForm($hour);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($hour = Doctrine::getTable('Hour')->find(array($request->getParameter('id'))), sprintf('Object hour does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new HourForm($hour);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($hour = Doctrine::getTable('Hour')->find(array($request->getParameter('id'))), sprintf('Object hour does not exist (%s).', array($request->getParameter('id'))));
    $hour->delete();

    $this->redirect('hour/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $hour = $form->save();

      $this->redirect('hour/edit?id='.$hour->getId());
    }
  }
}
