<?php
namespace Drupal\thxi_default_content\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

class ExtendedSiteInformationForm extends SiteInformationForm {
 
   /**
    * Build Form to extend
   * {@inheritdoc}
   */
	  public function buildForm(array $form, FormStateInterface $form_state) {
      // Get configuration of system site
      $site_config = $this->config('system.site');
      $form =  parent::buildForm($form, $form_state);
      $form['site_information']['siteapikey'] = [
        '#type' => 'textfield',
        '#title' => t('Site API Key'),
        '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
        '#description' => t("Custom field to set the API Key"),
      ];
      //Update save configuration button name
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Update Configuration'),
        '#button_type' => 'primary',
        ];
      return $form;
    }
    
    /**
      * Submit function for the build form
    */
	  public function submitForm(array &$form, FormStateInterface $form_state) {
      $site_config = $this->config('system.site');
      $flag = FALSE;
        if ($site_config->get('siteapikey') != $form_state->getValue('siteapikey')) {
          $flag = TRUE;
        }
      $this->config('system.site')
        ->set('siteapikey', $form_state->getValue('siteapikey'))
        ->save();
      // Flag for updating with site api key value
      if($flag == TRUE){
        \Drupal::messenger()->addStatus(t('Site api key has been set successfully with value %value.', ['%value' => $form_state->getValue('siteapikey')]));
      }
      parent::submitForm($form, $form_state);
	  }
}