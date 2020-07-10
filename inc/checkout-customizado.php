<?php

  function handel_custom_checkout($fields) {
    //$fields['billing']['billing_first_name']['label'] = 'Nome Completo';
    unset($fields['billing']['billing_phone']);
    $fields['billing']['billing_presente'] = [
      'label' => 'Embrulhar para Presente?',
      'required' => false,
      'class' => ['from-row-wide'],
      'clear' => true,
      'type' => 'select',
      'options' => [
        'nao' => 'Não',
        'sim' => 'Sim'
      ]
    ];
    return $fields;
  }



  function handel_custom_checkout_field($checkout){
    woocommerce_form_field( 'mensagem_personalizada', [
      'type' => 'textarea',
      'class' => ['form-row-wide mensagem-personalizada'],
      'label' => 'Mensagem Personalizada',
      'placeholder' => 'Escreva uma mensagem para a pessoa que você está presenteando.',
      'required' => true,
    ], $checkout->get_value('mensagem_personalizada'));
  }
  add_action('woocommerce_after_order_notes', 'handel_custom_checkout_field');

  function handel_custom_checkout_field_process(){
    if (!$_POST['mensagem_personalizada']) {
      wc_add_notice( 'Por Favor, escreva uma mensagem personalizada.', 'error');
    }
  }

  add_action('woocommerce_checkout_process', 'handel_custom_checkout_field_process');

  function handel_custom_checkout_field_update() {
    if (!empty($_POST['mensagem_personalizada'])) {
      update_post_cache($order_id, 'mensagem_personalizada', sanitize_text_field($_POST['mensagem_personalizada']));
    }
  }

  add_action('woocommerce_checkout_update_order_meta', 'handel_custom_checkout_field_update');


  add_filter('woocommerce_checkout_fields', 'handel_custom_checkout');

  function show_admin_custom_checkout_presente($order){
    $presente = get_post_meta($order->get_id(), '_billing_presente', true);
    $mensagem = get_post_meta($order->get_id(), 'mensagem_personalizada', true);
    echo '<p><strong>Presente: </strong>' . $presente . '</p>';
    echo '<p><strong>Mensagem Personalizada: </strong>' . $mensagem . '</p>';
  }

  add_action('woocommerce_admin_order_data_after_shipping_address', 'show_admin_custom_checkout_presente');
?>