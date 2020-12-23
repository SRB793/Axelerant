<?php
namespace Drupal\thxi_default_content\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm {
 
   /**
   * {@inheritdoc}
   */
	  public function buildForm(array $form, FormStateInterface $form_state) {
		$site_config = $this->config('system.site');
		$form =  parent::buildForm($form, $form_state);
		$form['site_information']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
			'#description' => t("Custom field to set the API Key"),
		];
		$form['actions']['submit'] = [
			'#type' => 'submit',
			'#value' => $this->t('Update Configuration'),
			'#button_type' => 'primary',
		  ];
		return $form;
	}
	
	  public function submitForm(array &$form, FormStateInterface $form_state) {
		$site_config = $this->config('system.site');
		$flag = FALSE;
        if ($site_config->get('siteapikey') != $form_state->getValue('siteapikey')) {
			$flag = TRUE;
		}
		$this->config('system.site')
		  ->set('siteapikey', $form_state->getValue('siteapikey'))
		  ->save();
		if($flag == TRUE){
			\Drupal::messenger()->addStatus(t('Site api key has been set successfully with value %value.', ['%value' => $form_state->getValue('siteapikey')]));
		}
		parent::submitForm($form, $form_state);
	  }
}