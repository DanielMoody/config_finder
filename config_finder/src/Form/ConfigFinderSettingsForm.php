<?php

namespace Drupal\config_finder\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigFinderSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames(): array {
    return ['config_finder.settings'];
  }

  public function getFormId(): string {
    return 'config_finder_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('config_finder.settings');

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message'),
      '#default_value' => $config->get('message') ?? '',
    ];

    $form['accessible_only'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide inaccessible or invalid configuration routes'),
      '#default_value' => $config->get('accessible_only') ?? TRUE,
      '#description' => $this->t('If checked, only configuration pages you can access will be shown.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('config_finder.settings')
         ->set('message', $form_state->getValue('message'))
         ->set('accessible_only', $form_state->getValue('accessible_only'))
         ->save();

    parent::submitForm($form, $form_state);
  }

}
