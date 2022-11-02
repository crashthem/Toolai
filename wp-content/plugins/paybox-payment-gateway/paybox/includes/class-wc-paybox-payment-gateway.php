<?php

/**
 * PayBox Payment Gateway
 *
 * Provides a PayBox Payment Gateway.
 *
 * @class  woocommerce_paybox
 * @package WooCommerce
 * @category Payment Gateways
 * @author PayBox
 * @license GPLv2
 */
class WC_PayBox_Payment_Gateway extends WC_Payment_Gateway {

    /**
     * Version
     *
     * @var string
     */
    public $version;

    /**
     * @access protected
     * @var array $data_to_send
     */
    protected $data_to_send = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->version = WC_GATEWAY_PAYBOX_VERSION;
        $this->id = 'paybox';
        $this->method_title       = __( 'PayBox', 'paybox-payment-gateway' );
        $this->method_description = sprintf( __( 'PayBox works by sending the user to %1$sPayBox%2$s to enter their payment information.', 'paybox-payment-gateway' ), '<a href="https://paybox.money/">', '</a>' );
        $this->icon               = WP_PLUGIN_URL . '/' . plugin_basename( dirname( dirname( __FILE__ ) ) ) . '/assets/images/icon.png';
        $this->debug_email        = get_option('admin_email');
        $this->available_countries  = array('KZ', 'RU', 'KG');
        $this->available_currencies = (array)apply_filters('woocommerce_gateway_paybox_available_currencies', array( 'KZT', 'RUR', 'RUB', 'USD', 'EUR', 'KGS', 'UZS' ) );

        $this->supports = array(
            'products',
            'pre-orders',
            'subscriptions',
            'subscription_cancellation',
            'subscription_suspension',
            'subscription_reactivation',
            'subscription_amount_changes',
            'subscription_date_changes',
            'subscription_payment_method_change'
        );
        $this->init_form_fields();
        $this->init_settings();

        if ( ! is_admin() ) {
            $this->setup_constants();
        }

        // Setup default merchant data.
        $this->merchant_id      = $this->get_option( 'merchant_id' );
        $this->merchant_key     = $this->get_option( 'merchant_key' );
        $this->pass_phrase      = $this->get_option( 'pass_phrase' );
        $this->title            = $this->get_option( 'title' );
        $this->response_url     = add_query_arg( 'wc-api', 'WC_PayBox_Payment_Gateway', home_url( '/' ) );
        $this->send_debug_email = 'yes' === $this->get_option( 'send_debug_email' );
        $this->description      = $this->get_option('description');
        $this->enabled          = $this->is_valid_for_use() ? 'yes': 'no'; // Check if the base currency supports this gateway.
        $this->enable_logging   = 'yes' === $this->get_option( 'enable_logging' );

        // Setup the test data, if in test mode.
        if ( 'yes' === $this->get_option( 'testmode' ) ) {
            $this->test_mode = true;
            $this->add_testmode_admin_settings_notice();
        } else {
            $this->send_debug_email = false;
        }

        add_action('woocommerce_check_cart_items', array($this, 'check_total'));
        add_action( 'woocommerce_api_wc_paybox_payment_gateway', array( $this, 'check_itn_response' ) );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_receipt_paybox', array( $this, 'receipt_page' ) );
        add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
        add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription_listener' ) );
        add_action( 'wc_pre_orders_process_pre_order_completion_payment_' . $this->id, array( $this, 'process_pre_order_payments' ) );
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Check price product
     *
     * @since 1.0.0
     */
    public function check_total() {
        $items = WC()->cart->get_cart();

        if (floatval(WC()->cart->get_cart_contents_total()) <=0 ) {
            foreach($items as $item => $values) {
                $product =  wc_get_product( $values['data']->get_id());
                $price = get_post_meta($values['product_id'] , '_price', true);

                if (floatval($price) <= 0) {
                    echo '<div class="inline error" style="border-color: firebrick; border-style: solid; margin-bottom: 5px; text-align: center; vertical-align: middle"><p style="">'
                        . __( 'Product ' . '<b>'.$product->get_title(). '</b>'. ' incorrect price.Payment form will not be generated', 'paybox-payment-gateway' )
                        . '</p></div>';
                }
            }
        }
    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @since 1.0.0
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'       => __( 'Enable/Disable', 'paybox-payment-gateway' ),
                'label'       => __( 'Enable PayBox', 'paybox-payment-gateway' ),
                'type'        => 'checkbox',
                'description' => __( 'This controls whether or not this gateway is enabled within WooCommerce.', 'paybox-payment-gateway' ),
                'default'     => 'yes',
                'desc_tip'    => true,
            ),
            'title' => array(
                'title'       => __( 'Title', 'paybox-payment-gateway' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'paybox-payment-gateway' ),
                'default'     => __( 'PayBox', 'paybox-payment-gateway' ),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __( 'Description', 'paybox-payment-gateway' ),
                'type'        => 'text',
                'description' => __( 'This controls the description which the user sees during checkout.', 'paybox-payment-gateway' ),
                'default'     => '',
                'desc_tip'    => true,
            ),
            'testmode' => array(
                'title'       => __( 'PayBox Test Mode', 'paybox-payment-gateway' ),
                'type'        => 'checkbox',
                'description' => __( 'Place the payment gateway in development mode.', 'paybox-payment-gateway' ),
                'default'     => 'yes',
            ),
            'merchant_id' => array(
                'title'       => __( 'Merchant ID', 'paybox-payment-gateway' ),
                'type'        => 'text',
                'description' => __( '* Required. This is the merchant ID, received from PayBox.', 'paybox-payment-gateway' ),
                'default'     => '',
            ),
            'merchant_key' => array(
                'title'       => __( 'Merchant Key', 'paybox-payment-gateway' ),
                'type'        => 'text',
                'description' => __( '* Required. This is the merchant key, received from PayBox.', 'paybox-payment-gateway' ),
                'default'     => '',
            ),
            'ofd' => array(
                'title' => __('OFD', 'paybox-payment-gateway'),
                'type' => 'checkbox',
                'description' => __('Enable generation of fiscal documents', 'paybox-payment-gateway'),
                'default' => ''
            ),
            'tax' => array(
                'title' => __('Type tax', 'paybox-payment-gateway'),
                'type' => 'select',
                'default' => '',
                'options' => array(
                    '0' => __('Without VAT (Webkassa, RocketR)', 'paybox-payment-gateway'),
                    '1' => __('0% (Webkassa, RocketR)', 'paybox-payment-gateway'),
                    '2' => __('12% (−)', 'paybox-payment-gateway'),
                    '3' => __('12/112 (Webkassa)', 'paybox-payment-gateway'),
                    '4' => __('18% (−)', 'paybox-payment-gateway'),
                    '5' => __('18/118 (−)', 'paybox-payment-gateway'),
                    '6' => __('10% (RocketR)', 'paybox-payment-gateway'),
                    '7' => __('10/110 (RocketR)', 'paybox-payment-gateway'),
                    '8' => __('20% (RocketR)', 'paybox-payment-gateway'),
                    '9' => __('20/120 (RocketR)', 'paybox-payment-gateway'),
                ),
            ),
            'success_status' => array(
                'title' => __('Successful payment', 'Order status after successful payment'),
                'type' => 'select',
                'default' => 'wc-processing',
                'options' => wc_get_order_statuses(),
            ),
            'failure_status' => array(
                'title' => __('Failure', 'Order status after failure'),
                'type' => 'select',
                'default' => 'wc-failed',
                'options' => wc_get_order_statuses(),
            ),
        );
    }

    /**
     * add_testmode_admin_settings_notice()
     * Add a notice to the merchant_key and merchant_id fields when in test mode.
     *
     * @since 1.0.0
     */
    public function add_testmode_admin_settings_notice() {
        $this->form_fields['merchant_id']['description']  .= ' <strong>' . __( 'Sandbox Merchant ID currently in use', 'paybox-payment-gateway' ) . ' ( ' . esc_html( $this->merchant_id ) . ' ).</strong>';
        $this->form_fields['merchant_key']['description'] .= ' <strong>' . __( 'Sandbox Merchant Key currently in use', 'paybox-payment-gateway' ) . ' ( ' . esc_html( $this->merchant_key ) . ' ).</strong>';
    }

    /**
     * is_valid_for_use()
     *
     * Check if this gateway is enabled and available in the base currency being traded with.
     *
     * @since 1.0.0
     * @return bool
     */
    public function is_valid_for_use() {
        $is_available          = false;
        $is_available_currency = in_array( get_woocommerce_currency(), $this->available_currencies );

        if ( $is_available_currency && $this->merchant_id && $this->merchant_key ) {
            $is_available = true;
        }

        return $is_available;
    }

    /**
     * Admin Panel Options
     * - Options for bits like 'title' and availability on a country-by-country basis
     *
     * @since 1.0.0
     */
    public function admin_options() {
        if ( in_array( get_woocommerce_currency(), $this->available_currencies ) ) {
            ?>

            <h3><?php
                echo (!empty($this->method_title)) ? $this->method_title : __('Settings', 'woocommerce'); ?></h3>

            <?php
            echo (!empty($this->method_description)) ? wpautop($this->method_description) : ''; ?>

            <script type="application/javascript">
                jQuery(document).ready(function () {
                    if (jQuery('input[name="woocommerce_paybox_ofd"]').is(':checked')) {
                        jQuery('input[name="woocommerce_paybox_tax"]').prop("disabled", false);
                    } else {
                        jQuery('input[name="woocommerce_paybox_tax"]').prop("disabled", true);
                    }

                    jQuery('input[name="woocommerce_paybox_ofd"]').change(function () {
                        if (this.checked) {
                            jQuery('input[name="woocommerce_paybox_tax"]').prop("disabled", false);
                        } else {
                            jQuery('input[name="woocommerce_paybox_tax"]').prop("disabled", true);
                        }
                    })
                })
            </script>
            <table class="form-table">
                <?php
                $this->generate_settings_html(); ?>
            </table><?php
        } else {
            ?>
            <h3><?php _e( 'PayBox', 'paybox-payment-gateway' ); ?></h3>
            <div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'paybox-payment-gateway' ); ?></strong> <?php /* translators: 1: a href link 2: closing href */ echo sprintf( __( 'Choose KZT, RUR, USD, EUR or KGS as your store currency in %1$sGeneral Settings%2$s to enable the PayBox Gateway.', 'paybox-payment-gateway' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=general' ) ) . '">', '</a>' ); ?></p></div>
            <?php
        }
    }

    /**
     * Generate the PayBox button link.
     *
     * @since 1.0.0
     */
    public function generate_PayBox_form( $order_id ) {
        $order = wc_get_order( $order_id );

        // Construct variables for post
        $orderId = (!empty($order_id))
            ? $order_id
            : (!empty(self::get_order_prop( $order, 'id' ))
                ? self::get_order_prop( $order, 'id' )
                : (!empty($order->get_order_number())
                    ? $order->get_order_number()
                    : 0)
            );

        if (method_exists($order, 'get_currency')) {
            $currency = $order->get_currency();
        } else {
            $currency = $order->get_order_currency();
        }

        $this->data_to_send = array(
            'pg_amount'         => $order->get_total(),
            'pg_description'    => sprintf( __( 'New order from %s', 'paybox-payment-gateway' ), get_bloginfo( 'name' ) ),
            'pg_encoding'       => 'UTF-8',
            'pg_currency'       => $currency,
            'pg_user_ip'        => $_SERVER['REMOTE_ADDR'],
            'pg_lifetime'       => 86400,
            'pg_merchant_id'    => $this->merchant_id,
            'pg_order_id'       => $orderId,
            'pg_result_url'     => $this->response_url,
            'pg_request_method' => 'POST',
            'pg_salt'           => rand(21, 43433),
            'pg_success_url'    => get_site_url().'/checkout/order-received/',
            'pg_failure_url'	=> get_site_url(),
            'pg_user_phone'     => preg_replace('/[^0-9]/', '', self::get_order_prop($order, 'billing_phone')),
            'pg_user_contact_email' => self::get_order_prop( $order, 'billing_email' )
        );
        $this->data_to_send['pg_testing_mode'] = ('yes' === $this->get_option( 'testmode' )) ? 1 : 0;

        if ('yes' === $this->get_option('ofd')) {
            $order = wc_get_order($order_id);
            $tax_type = $this->get_option('tax');

            foreach ($order->get_items() as $item_id => $item) {
                $this->data_to_send['pg_receipt_positions'][] = [
                    'count' => $order->get_item_meta($item_id, '_qty', true),
                    'name' => $item['name'],
                    'price' => $item->get_product()->get_price(),
                    'tax_type' => $tax_type
                ];
            }
        }

        $this->log("data for paybox: " . var_export($this->data_to_send, 1));

        $sign_data = $this->prepare_request_data($this->data_to_send);

        $url = 'payment.php';
        ksort($sign_data);
        array_unshift($sign_data, $url);
        $sign_data[] = $this->merchant_key;
        $str = implode(';', $sign_data);
        $this->data_to_send['pg_sig'] = md5($str);
        $query = http_build_query($this->data_to_send);
        $this->url = 'https://api.paybox.money/' . $url . '?' . $query;

        // add subscription parameters
        if ( $this->order_contains_subscription( $order_id ) ) {
            // 2 == ad-hoc subscription type see PayBox API docs
            $this->data_to_send['subscription_type'] = '2';
        }

        if ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order ) ) {
            $subscriptions = wcs_get_subscriptions_for_renewal_order( $order_id );
            // For renewal orders that have subscriptions with renewal flag,
            // we will create a new subscription in PayBox and link it to the existing ones in WC.
            // The old subscriptions in PayBox will be cancelled once we handle the itn request.
            if ( count ( $subscriptions ) > 0 && $this->_has_renewal_flag( reset( $subscriptions ) ) ) {
                // 2 == ad-hoc subscription type see PayBox API docs
                $this->data_to_send['subscription_type'] = '2';
            }
        }

        // pre-order: add the subscription type for pre order that require tokenization
        // at this point we assume that the order pre order fee and that
        // we should only charge that on the order. The rest will be charged later.
        if ( $this->order_contains_pre_order( $order_id )
            && $this->order_requires_payment_tokenization( $order_id ) ) {
            $this->data_to_send['amount']            = $this->get_pre_order_fee( $order_id );
            $this->data_to_send['subscription_type'] = '2';
        }

        return '<form action="' . esc_url( $this->url ) . '" method="post" id="PayBox_payment_form">
                ' . implode( '', $this->get_input($this->data_to_send) ) . '
                <input type="submit" class="button-alt" id="submit_PayBox_payment_form" value="' . __( 'Pay via PayBox', 'paybox-payment-gateway' ) . '" /> <a class="button cancel" href="' . $order->get_cancel_order_url() . '">' . __( 'Cancel order &amp; restore cart', 'paybox-payment-gateway' ) . '</a>
                <script type="text/javascript">
                    jQuery(function(){
                        jQuery("body").block(
                            {
                                message: "' . __( 'Thank you for your order. We are now redirecting you to PayBox to make payment.', 'paybox-payment-gateway' ) . '",
                                overlayCSS:
                                {
                                    background: "#fff",
                                    opacity: 0.6
                                },
                                css: {
                                    padding:        20,
                                    textAlign:      "center",
                                    color:          "#555",
                                    border:         "3px solid #aaa",
                                    backgroundColor:"#fff",
                                    cursor:         "wait"
                                }
                            });
                        jQuery( "#submit_PayBox_payment_form" ).click();
                    });
                </script>
            </form>';
    }

    /**
     * @param $data
     * @param string $parent_input_name
     * @return array
     */
    public function get_input($data, $parent_input_name = '')
    {
        $result = [];

        foreach ($data as $field_name => $field_value) {
            $name = $field_name;

            if ('' !== $parent_input_name) {
                $name = $parent_input_name . '[' . $field_name . ']';
            }

            if (is_array($field_value)) {
                $result[] = implode("\n", $this->get_input($field_value, (string)$name));
            } else {
                $result[] = '<input type="hidden" name="' . $name . '" value="' . esc_attr($field_value) . '" />';
            }
        }

        return $result;
    }

    /**
     * Process the payment and return the result.
     *
     * @since 1.0.0
     */
    public function process_payment( $order_id ) {

        if ( $this->order_contains_pre_order( $order_id )
            && $this->order_requires_payment_tokenization( $order_id )
            && ! $this->cart_contains_pre_order_fee() ) {
            throw new Exception( 'PayBox does not support transactions without any upfront costs or fees. Please select another gateway' );
        }

        $order = wc_get_order( $order_id );
        return array(
            'result'      => 'success',
            'redirect'     => $order->get_checkout_payment_url( true ),
        );
    }

    /**
     * Reciept page.
     *
     * Display text and a button to direct the user to PayBox.
     *
     * @since 1.0.0
     */
    public function receipt_page( $order ) {
        echo '<p>' . __( 'Thank you for your order, please click the button below to pay with PayBox.', 'paybox-payment-gateway' ) . '</p>';
        echo $this->generate_PayBox_form( $order );
    }

    /**
     * Check PayBox ITN response.
     *
     * @since 1.0.0
     */
    public function check_itn_response() {
        $this->handle_itn_request( stripslashes_deep( $_POST ) );
//        $this->handle_itn_request( $_GET );

        // Notify PayBox that information has been received
        header( 'HTTP/1.0 200 OK' );
        flush();
    }

    /**
     * Check PayBox ITN validity.
     *
     * @param array $data
     * @since 1.0.0
     */
    public function handle_itn_request( $data ) {
        $this->log( PHP_EOL
            . '----------'
            . PHP_EOL . 'PayBox ITN call received'
            . PHP_EOL . '----------'
        );
        $this->log( 'Get sent data' );
        $this->log( 'PayBox Data: ' . print_r( $data, true ) );

        $PayBox_error  = false;
        $PayBox_done   = false;
        $debug_email    = $this->get_option( 'debug_email', get_option( 'admin_email' ) );
        $session_id     = $data['custom_str1'];
        $vendor_name    = get_bloginfo( 'name' );
        $vendor_url     = home_url( '/' );
        $order_id       = absint( $data['custom_str3'] );
        $order_key      = wc_clean( $session_id );
        $order          = wc_get_order( $order_id );
        $original_order = $order;

        if(!empty($_REQUEST['pg_order_id'])) {
            if(isset($_REQUEST['pg_result'])) {
                $order = wc_get_order( $_REQUEST['pg_order_id'] );
                if($_REQUEST['pg_result'] == 1) {
                    if ($order->get_status() == 'pending' || $order->get_status() == 'on-hold') {
                        $this->log("updating order status");
                        $order->update_status($this->get_option('success_status'), __( 'PayBox Order payment success', 'paybox-payment-gateway' ));
                        $this->log("order status is set to " . $order->get_status());
                    }
                    $orderId = (!empty($order_id))
                        ? $order_id
                        : (!empty(self::get_order_prop( $order, 'id' ))
                            ? self::get_order_prop( $order, 'id' )
                            : (!empty($order->get_order_number())
                                ? $order->get_order_number()
                                : 0)
                        );
                    header('Location:/');
                } else {
                    if ($order->get_status() == 'pending' || $order->get_status() == 'on-hold') {
                        $order->update_status($this->get_option('failure_status'), __( 'PayBox Order payment failed', 'paybox-payment-gateway' ));
                    }
                    header('Location:/');
                }
            }
        }

        $this->log( PHP_EOL
            . '----------'
            . PHP_EOL . 'End ITN call'
            . PHP_EOL . '----------'
        );

    }

    /**
     * Handle logging the order details.
     *
     * @since 1.4.5
     */
    public function log_order_details( $order ) {
        if ( version_compare( WC_VERSION,'3.0.0', '<' ) ) {
            $customer_id = get_post_meta( $order->get_id(), '_customer_user', true );
        } else {
            $customer_id = $order->get_user_id();
        }

        $details = "Order Details:"
            . PHP_EOL . 'customer id:' . $customer_id
            . PHP_EOL . 'order id:   ' . $order->get_id()
            . PHP_EOL . 'parent id:  ' . $order->get_parent_id()
            . PHP_EOL . 'status:     ' . $order->get_status()
            . PHP_EOL . 'total:      ' . $order->get_total()
            . PHP_EOL . 'currency:   ' . $order->get_currency()
            . PHP_EOL . 'key:        ' . $order->get_order_key()
            . "";

        $this->log( $details );
    }

    /**
     * This function mainly responds to ITN cancel requests initiated on PayBox, but also acts
     * just in case they are not cancelled.
     * @version 1.4.3 Subscriptions flag
     *
     * @param array $data should be from the Gatewy ITN callback.
     * @param WC_Order $order
     */
    public function handle_itn_payment_cancelled( $data, $order, $subscriptions ) {

        remove_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription_listener' ) );
        foreach ( $subscriptions as $subscription ) {
            if ( 'cancelled' !== $subscription->get_status() ) {
                $subscription->update_status( 'cancelled', __( 'Merchant cancelled subscription on PayBox.' , 'paybox-payment-gateway' ) );
                $this->_delete_subscription_token( $subscription );
            }
        }
        add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription_listener' ) );
    }

    /**
     * This function handles payment complete request by PayBox.
     * @version 1.4.3 Subscriptions flag
     *
     * @param array $data should be from the Gatewy ITN callback.
     * @param WC_Order $order
     */
    public function handle_itn_payment_complete( $data, $order, $subscriptions ) {
        $this->log( '- Complete' );
        $order->add_order_note( __( 'ITN payment completed', 'paybox-payment-gateway' ) );
        $order_id = self::get_order_prop( $order, 'id' );

        // Store token for future subscription deductions.
        if ( count( $subscriptions ) > 0 && isset( $data['token'] ) ) {
            if ( $this->_has_renewal_flag( reset( $subscriptions ) ) ) {
                // renewal flag is set to true, so we need to cancel previous token since we will create a new one
                $this->log( 'Cancel previous subscriptions with token ' . $this->_get_subscription_token( reset( $subscriptions ) ) );

                // only request API cancel token for the first subscription since all of them are using the same token
                $this->cancel_subscription_listener( reset( $subscriptions ) );
            }

            $token = sanitize_text_field( $data['token'] );
            foreach ( $subscriptions as $subscription ) {
                $this->_delete_renewal_flag( $subscription );
                $this->_set_subscription_token( $token, $subscription );
            }
        }

        // the same mechanism (adhoc token) is used to capture payment later
        if ( $this->order_contains_pre_order( $order_id )
            && $this->order_requires_payment_tokenization( $order_id ) ) {

            $token = sanitize_text_field( $data['token'] );
            $is_pre_order_fee_paid = get_post_meta( $order_id, '_pre_order_fee_paid', true ) === 'yes';

            if ( ! $is_pre_order_fee_paid ) {
                /* translators: 1: gross amount 2: payment id */
                $order->add_order_note( sprintf( __( 'PayBox pre-order fee paid: R %1$s (%2$s)', 'paybox-payment-gateway' ), $data['amount_gross'], $data['pf_payment_id'] ) );
                $this->_set_pre_order_token( $token, $order );
                // set order to pre-ordered
                WC_Pre_Orders_Order::mark_order_as_pre_ordered( $order );
                update_post_meta( $order_id, '_pre_order_fee_paid', 'yes' );
                WC()->cart->empty_cart();
            } else {
                /* translators: 1: gross amount 2: payment id */
                $order->add_order_note( sprintf( __( 'PayBox pre-order product line total paid: R %1$s (%2$s)', 'paybox-payment-gateway' ), $data['amount_gross'], $data['pf_payment_id'] ) );
                $order->payment_complete();
                $this->cancel_pre_order_subscription( $token );
            }
        } else {
            $order->payment_complete();
        }

        $debug_email   = $this->get_option( 'debug_email', get_option( 'admin_email' ) );
        $vendor_name    = get_bloginfo( 'name' );
        $vendor_url     = home_url( '/' );
        if ( $this->send_debug_email ) {
            $subject = 'PayBox ITN on your site';
            $body =
                "Hi,\n\n"
                . "A PayBox transaction has been completed on your website\n"
                . "------------------------------------------------------------\n"
                . 'Site: ' . $vendor_name . ' (' . $vendor_url . ")\n"
                . 'Purchase ID: ' . esc_html( $data['m_payment_id'] ) . "\n"
                . 'PayBox Transaction ID: ' . esc_html( $data['pf_payment_id'] ) . "\n"
                . 'PayBox Payment Status: ' . esc_html( $data['payment_status'] ) . "\n"
                . 'Order Status Code: ' . self::get_order_prop( $order, 'status' );
            wp_mail( $debug_email, $subject, $body );
        }
    }

    /**
     * @param $data
     * @param $order
     */
    public function handle_itn_payment_failed( $data, $order ) {
        $this->log( '- Failed' );
        /* translators: 1: payment status */
        $order->update_status($this->get_option('failure_status'), sprintf( __( 'Payment %s via ITN.', 'paybox-payment-gateway' ), strtolower( sanitize_text_field( $data['payment_status'] ) ) ) );
        $debug_email   = $this->get_option( 'debug_email', get_option( 'admin_email' ) );
        $vendor_name    = get_bloginfo( 'name' );
        $vendor_url     = home_url( '/' );

        if ( $this->send_debug_email ) {
            $subject = 'PayBox ITN Transaction on your site';
            $body =
                "Hi,\n\n" .
                "A failed PayBox transaction on your website requires attention\n" .
                "------------------------------------------------------------\n" .
                'Site: ' . $vendor_name . ' (' . $vendor_url . ")\n" .
                'Purchase ID: ' . self::get_order_prop( $order, 'id' ) . "\n" .
                'User ID: ' . self::get_order_prop( $order, 'user_id' ) . "\n" .
                'PayBox Transaction ID: ' . esc_html( $data['pf_payment_id'] ) . "\n" .
                'PayBox Payment Status: ' . esc_html( $data['payment_status'] );
            wp_mail( $debug_email, $subject, $body );
        }
    }

    /**
     * @since 1.4.0 introduced
     * @param $data
     * @param $order
     */
    public function handle_itn_payment_pending( $data, $order ) {
        $this->log( '- Pending' );
        // Need to wait for "Completed" before processing
        /* translators: 1: payment status */
        $order->update_status( 'on-hold', sprintf( __( 'Payment %s via ITN.', 'paybox-payment-gateway' ), strtolower( sanitize_text_field( $data['payment_status'] ) ) ) );
    }

    /**
     * @param string $order_id
     * @return double
     */
    public function get_pre_order_fee( $order_id ) {
        foreach ( wc_get_order( $order_id )->get_fees() as $fee ) {
            if ( is_array( $fee ) && 'Pre-Order Fee' == $fee['name'] ) {
                return doubleval( $fee['line_total'] ) + doubleval( $fee['line_tax'] );
            }
        }
    }
    /**
     * @param string $order_id
     * @return bool
     */
    public function order_contains_pre_order( $order_id ) {
        if ( class_exists( 'WC_Pre_Orders_Order' ) ) {
            return WC_Pre_Orders_Order::order_contains_pre_order( $order_id );
        }
        return false;
    }

    /**
     * @param string $order_id
     *
     * @return bool
     */
    public function order_requires_payment_tokenization( $order_id ) {
        if ( class_exists( 'WC_Pre_Orders_Order' ) ) {
            return WC_Pre_Orders_Order::order_requires_payment_tokenization( $order_id );
        }
        return false;
    }

    /**
     * @return bool
     */
    public function cart_contains_pre_order_fee() {
        if ( class_exists( 'WC_Pre_Orders_Cart' ) ) {
            return WC_Pre_Orders_Cart::cart_contains_pre_order_fee();
        }
        return false;
    }
    /**
     * Store the PayBox subscription token
     *
     * @param string $token
     * @param WC_Subscription $subscription
     */
    protected function _set_subscription_token( $token, $subscription ) {
        update_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_subscription_token', $token );
    }

    /**
     * Retrieve the PayBox subscription token for a given order id.
     *
     * @param WC_Subscription $subscription
     * @return mixed
     */
    protected function _get_subscription_token( $subscription ) {
        return get_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_subscription_token', true );
    }

    /**
     * Retrieve the PayBox subscription token for a given order id.
     *
     * @param WC_Subscription $subscription
     * @return mixed
     */
    protected function _delete_subscription_token( $subscription ) {
        return delete_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_subscription_token' );
    }

    /**
     * Store the PayBox renewal flag
     * @since 1.4.3
     *
     * @param string $token
     * @param WC_Subscription $subscription
     */
    protected function _set_renewal_flag( $subscription ) {
        if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
            update_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_renewal_flag', 'true' );
        } else {
            $subscription->update_meta_data( '_PayBox_renewal_flag', 'true' );
            $subscription->save_meta_data();
        }
    }

    /**
     * Retrieve the PayBox renewal flag for a given order id.
     * @since 1.4.3
     *
     * @param WC_Subscription $subscription
     * @return bool
     */
    protected function _has_renewal_flag( $subscription ) {
        if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
            return 'true' === get_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_renewal_flag', true );
        } else {
            return 'true' === $subscription->get_meta( '_PayBox_renewal_flag', true );
        }
    }

    /**
     * Retrieve the PayBox renewal flag for a given order id.
     * @since 1.4.3
     *
     * @param WC_Subscription $subscription
     * @return mixed
     */
    protected function _delete_renewal_flag( $subscription ) {
        if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
            return delete_post_meta( self::get_order_prop( $subscription, 'id' ), '_PayBox_renewal_flag' );
        } else {
            $subscription->delete_meta_data( '_PayBox_renewal_flag' );
            $subscription->save_meta_data();
        }
    }

    /**
     * Store the PayBox pre_order_token token
     *
     * @param string $token
     * @param WC_Order$order
     */
    protected function _set_pre_order_token( $token, $order ) {
        update_post_meta( self::get_order_prop( $order, 'id' ), '_PayBox_pre_order_token', $token );
    }

    /**
     * Retrieve the PayBox pre-order token for a given order id.
     *
     * @param WC_Order $order
     * @return mixed
     */
    protected function _get_pre_order_token( $order ) {
        return get_post_meta( self::get_order_prop( $order, 'id' ), '_PayBox_pre_order_token', true );
    }

    /**
     * Wrapper function for wcs_order_contains_subscription
     *
     * @param WC_Order $order
     * @return bool
     */
    public function order_contains_subscription( $order ) {
        if ( ! function_exists( 'wcs_order_contains_subscription' ) ) {
            return false;
        }
        return wcs_order_contains_subscription( $order );
    }

    /**
     * @param $amount_to_charge
     * @param WC_Order $renewal_order
     */
    public function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {

        $subscription = wcs_get_subscription( get_post_meta( self::get_order_prop( $renewal_order, 'id' ), '_subscription_renewal', true ) );
        $this->log( 'Attempting to renew subscription from renewal order ' . self::get_order_prop( $renewal_order, 'id' ) );

        if ( empty( $subscription ) ) {
            $this->log( 'Subscription from renewal order was not found.' );
            return;
        }

        $response = $this->submit_subscription_payment( $subscription, $amount_to_charge );

        if ( is_wp_error( $response ) ) {
            /* translators: 1: error code 2: error message */
            $renewal_order->update_status($this->get_option('failure_status'), sprintf( __( 'PayBox Subscription renewal transaction failed (%1$s:%2$s)', 'paybox-payment-gateway' ), $response->get_error_code() ,$response->get_error_message() ) );
        }
        // Payment will be completion will be capture only when the ITN callback is sent to $this->handle_itn_request().
        $renewal_order->add_order_note( __( 'PayBox Subscription renewal transaction submitted.', 'paybox-payment-gateway' ) );

    }

    /**
     * Get a name for the subscription item. For multiple
     * item only Subscription $date will be returned.
     *
     * For subscriptions with no items Site/Blog name will be returned.
     *
     * @param WC_Subscription $subscription
     * @return string
     */
    public function get_subscription_name( $subscription ) {

        if ( $subscription->get_item_count() > 1 ) {
            return $subscription->get_date_to_display( 'start' );
        } else {
            $items = $subscription->get_items();

            if ( empty( $items ) ) {
                return get_bloginfo( 'name' );
            }

            $item = array_shift( $items );
            return $item['name'];
        }
    }


    /**
     * Responds to Subscriptions extension cancellation event.
     *
     * @since 1.4.0 introduced.
     * @param WC_Subscription $subscription
     */
    public function cancel_subscription_listener( $subscription ) {
        $token = $this->_get_subscription_token( $subscription );
        if ( empty( $token ) ) {
            return;
        }
        $this->api_request( 'cancel', $token, array(), 'PUT' );
    }

    /**
     * @since 1.4.0
     * @param string $token
     *
     * @return bool|WP_Error
     */
    public function cancel_pre_order_subscription( $token ) {
        return $this->api_request( 'cancel', $token, array(), 'PUT' );
    }

    /**
     * @since 1.4.0 introduced.
     * @param      $api_data
     * @param bool $sort_data_before_merge? default true.
     * @param bool $skip_empty_values Should key value pairs be ignored when generating signature?  Default true.
     *
     * @return string
     */
    protected function _generate_parameter_string( $api_data, $sort_data_before_merge = true, $skip_empty_values = true ) {

        // if sorting is required the passphrase should be added in before sort.
        if ( ! empty( $this->pass_phrase ) && $sort_data_before_merge ) {
            $api_data['passphrase'] = $this->pass_phrase;
        }

        if ( $sort_data_before_merge ) {
            ksort( $api_data );
        }

        // concatenate the array key value pairs.
        $parameter_string = '';
        foreach ( $api_data as $key => $val ) {

            if ( $skip_empty_values && empty( $val ) ) {
                continue;
            }

            if ( 'signature' !== $key ) {
                $val = urlencode( $val );
                $parameter_string .= "$key=$val&";
            }
        }
        // when not sorting passphrase should be added to the end before md5
        if ( $sort_data_before_merge ) {
            $parameter_string = rtrim( $parameter_string, '&' );
        } elseif ( ! empty( $this->pass_phrase ) ) {
            $parameter_string .= 'passphrase=' . urlencode( $this->pass_phrase );
        } else {
            $parameter_string = rtrim( $parameter_string, '&' );
        }

        return $parameter_string;
    }
    /**
     * Setup constants.
     *
     * Setup common values and messages used by the PayBox gateway.
     *
     * @since 1.0.0
     */
    public function setup_constants() {
        // Create user agent string.
        define( 'PF_SOFTWARE_NAME', 'WooCommerce' );
        define( 'PF_SOFTWARE_VER', WC_VERSION );
        define( 'PF_MODULE_NAME', 'WooCommerce-paybox-Free' );
        define( 'PF_MODULE_VER', $this->version );

        // Features
        // - PHP
        $pf_features = 'PHP ' . phpversion() . ';';

        // - cURL
        if ( in_array( 'curl', get_loaded_extensions() ) ) {
            define( 'PF_CURL', '' );
            $pf_version = curl_version();
            $pf_features .= ' curl ' . $pf_version['version'] . ';';
        } else {
            $pf_features .= ' nocurl;';
        }

        // Create user agrent
        define( 'PF_USER_AGENT', PF_SOFTWARE_NAME . '/' . PF_SOFTWARE_VER . ' (' . trim( $pf_features ) . ') ' . PF_MODULE_NAME . '/' . PF_MODULE_VER );

        // General Defines
        define( 'PF_TIMEOUT', 15 );
        define( 'PF_EPSILON', 0.01 );

        // Messages
        // Error
        define( 'PF_ERR_AMOUNT_MISMATCH', __( 'Amount mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_BAD_ACCESS', __( 'Bad access of page', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_BAD_SOURCE_IP', __( 'Bad source IP address', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_CONNECT_FAILED', __( 'Failed to connect to PayBox', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_INVALID_SIGNATURE', __( 'Security signature mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_MERCHANT_ID_MISMATCH', __( 'Merchant ID mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_NO_SESSION', __( 'No saved session found for ITN transaction', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_ORDER_ID_MISSING_URL', __( 'Order ID not present in URL', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_ORDER_ID_MISMATCH', __( 'Order ID mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_ORDER_INVALID', __( 'This order ID is invalid', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_ORDER_NUMBER_MISMATCH', __( 'Order Number mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_ORDER_PROCESSED', __( 'This order has already been processed', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_PDT_FAIL', __( 'PDT query failed', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_PDT_TOKEN_MISSING', __( 'PDT token not present in URL', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_SESSIONID_MISMATCH', __( 'Session ID mismatch', 'paybox-payment-gateway' ) );
        define( 'PF_ERR_UNKNOWN', __( 'Unkown error occurred', 'paybox-payment-gateway' ) );

        // General
        define( 'PF_MSG_OK', __( 'Payment was successful', 'paybox-payment-gateway' ) );
        define( 'PF_MSG_FAILED', __( 'Payment has failed', 'paybox-payment-gateway' ) );
        define( 'PF_MSG_PENDING', __( 'The payment is pending. Please note, you will receive another Instant Transaction Notification when the payment status changes to "Completed", or "Failed"', 'paybox-payment-gateway' ) );

        do_action( 'woocommerce_gateway_PayBox_setup_constants' );
    }

    /**
     * Log system processes.
     * @since 1.0.0
     */
    public function log( $message ) {
        if ( 'yes' === $this->get_option( 'testmode' ) || $this->enable_logging ) {
            if ( empty( $this->logger ) ) {
                $this->logger = new WC_Logger();
            }
            $this->logger->add( 'PayBox', $message );
        }
    }

    /**
     * validate_signature()
     *
     * Validate the signature against the returned data.
     *
     * @param array $data
     * @param string $signature
     * @since 1.0.0
     * @return string
     */
    public function validate_signature( $data, $signature ) {
        $result = $data['signature'] === $signature;
        $this->log( 'Signature = ' . ( $result ? 'valid' : 'invalid' ) );
        return $result;
    }

    /**
     * Validate the IP address to make sure it's coming from PayBox.
     *
     * @param array $source_ip
     * @since 1.0.0
     * @return bool
     */
    public function is_valid_ip( $source_ip ) {
        // Variable initialization
        $valid_hosts = array(
            'www.PayBox.co.za',
            'sandbox.PayBox.co.za',
            'w1w.PayBox.co.za',
            'w2w.PayBox.co.za',
        );

        $valid_ips = array();

        foreach ( $valid_hosts as $pf_hostname ) {
            $ips = gethostbynamel( $pf_hostname );

            if ( false !== $ips ) {
                $valid_ips = array_merge( $valid_ips, $ips );
            }
        }

        // Remove duplicates
        $valid_ips = array_unique( $valid_ips );

        $this->log( "Valid IPs:\n" . print_r( $valid_ips, true ) );
        $is_valid_ip = in_array( $source_ip, $valid_ips );
        return apply_filters( 'woocommerce_gateway_PayBox_is_valid_ip', $is_valid_ip, $source_ip );
    }

    /**
     * validate_response_data()
     *
     * @param array $post_data
     * @param string $proxy Address of proxy to use or NULL if no proxy.
     * @since 1.0.0
     * @return bool
     */
    public function validate_response_data( $post_data, $proxy = null ) {
        $this->log( 'Host = ' . $this->validate_url );
        $this->log( 'Params = ' . print_r( $post_data, true ) );

        if ( ! is_array( $post_data ) ) {
            return false;
        }

        $response = wp_remote_post( $this->validate_url, array(
            'body'       => $post_data,
            'timeout'    => 70,
            'user-agent' => PF_USER_AGENT,
        ));

        if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
            $this->log( "Response error:\n" . print_r( $response, true ) );
            return false;
        }

        parse_str( $response['body'], $parsed_response );

        $response = $parsed_response;

        $this->log( "Response:\n" . print_r( $response, true ) );

        // Interpret Response
        if ( is_array( $response ) && in_array( 'VALID', array_keys( $response ) ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * amounts_equal()
     *
     * Checks to see whether the given amounts are equal using a proper floating
     * point comparison with an Epsilon which ensures that insignificant decimal
     * places are ignored in the comparison.
     *
     * eg. 100.00 is equal to 100.0001
     *
     * @param $amount1 Float 1st amount for comparison
     * @param $amount2 Float 2nd amount for comparison
     * @since 1.0.0
     * @return bool
     */
    public function amounts_equal( $amount1, $amount2 ) {
        return ! ( abs( floatval( $amount1 ) - floatval( $amount2 ) ) > PF_EPSILON );
    }

    /**
     * Get order property with compatibility check on order getter introduced
     * in WC 3.0.
     *
     * @since 1.4.1
     *
     * @param WC_Order $order Order object.
     * @param string   $prop  Property name.
     *
     * @return mixed Property value
     */
    public static function get_order_prop( $order, $prop ) {
        switch ( $prop ) {
            case 'order_total':
                $getter = array( $order, 'get_total' );
                break;
            default:
                $getter = array( $order, 'get_' . $prop );
                break;
        }

        return is_callable( $getter ) ? call_user_func( $getter ) : $order->{ $prop };
    }

    /**
     *  Show possible admin notices
     *
     */
    public function admin_notices() {
        if('yes' == $this->get_option( 'enabled' )) {
            if(empty($this->merchant_id)) {
                echo '<div class="error paybox-passphrase-message"><p>'
                    . __( 'PayBox requires a Merchant ID to work.', 'paybox-payment-gateway' )
                    . '</p></div>';
            }
            if(empty($this->merchant_key)) {
                echo '<div class="error paybox-passphrase-message"><p>'
                    . __( 'PayBox required a Merchant Key to work.', 'paybox-payment-gateway' )
                    . '</p></div>';
            }
        }
    }

    /**
     * @param $data
     * @param string $parent_name
     * @return array|string[]
     */
    private function prepare_request_data($data, $parent_name = '')
    {
        if (!is_array($data)) {
            return $data;
        }

        $result = [];
        $i = 0;

        foreach ($data as $key => $val) {
            $name = $parent_name . ((string)$key) . sprintf('%03d', ++$i);

            if (is_array($val)) {
                $result = array_merge($result, $this->prepare_request_data($val, $name));
                continue;
            }

            $result += [$name => (string)$val];
        }

        return $result;
    }
}
