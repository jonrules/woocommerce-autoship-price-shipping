<?php
class WC_Autoship_Price_Shipping extends WC_Shipping_Method {
	/**
	 * Constructor for your shipping class
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->id = 'wc_autoship_price_shipping';
		$this->title = __( 'WC Auto-Ship Price Shipping', 'wc-autoship-price-shipping' );
		$this->method_title = __( 'WC Auto-Ship Price Shipping', 'wc-autoship-price-shipping' );
		$this->method_description = __( 'Price-based shipping rates for WC Auto-Ship' );
		$this->enabled = $this->get_option( 'enabled' );
		$this->has_settings = true;
		$this->init();
	}

	/**
	 * Init your settings
	 *
	 * @access public
	 * @return void
	 */
	function init() {
		// Load the settings API
		$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
		$this->init_settings(); // This is part of the settings API. Loads settings you previously init.

		// Save settings in admin
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}
	
	/**
	 * Initialise Gateway Settings Form Fields
	 */
	function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', 'wc-autoship-price-shipping' ),
				'type' => 'checkbox',
				'label' => __( 'Enable this shipping method', 'wc-autoship-price-shipping' ),
				'default' => 'yes',
			)
		);
	}
	
	/**
	 * Admin Options
	 *
	 * Setup the gateway settings screen.
	 */
	public function admin_options() {
		parent::admin_options();
		
		$rates = get_option( 'wc_autoship_price_shipping_rates' );
		if ( ! $rates ) {
			$rates = array();
		}
		include( dirname( dirname( __FILE__ ) ) . '/templates/rates-table.php' );
	}
	
	public function process_admin_options() {
		$success = parent::process_admin_options();
		
		if ( isset( $_POST['wc_autoship_price_shipping_rates'] ) && is_array( $_POST['wc_autoship_price_shipping_rates'] ) ) {
			$rates = array();
			foreach ( $_POST['wc_autoship_price_shipping_rates'] as $rate ) {
				if ( $rate['min_subtotal'] !== '' && $rate['cost'] !== '' ) {
					$rate['min_subtotal'] = floatval( $rate['min_subtotal'] );
					$rate['cost'] = floatval( $rate['cost'] );
					$rates[] = $rate;
				}
			}
			usort( $rates , 'wc_autoship_price_shipping_compare_min_subtotal' );
			update_option( 'wc_autoship_price_shipping_rates', $rates );
		} else {
			update_option( 'wc_autoship_price_shipping_rates', array() );
		}
		
		return $success;
	}

	/**
	 * calculate_shipping function.
	 *
	 * @access public
	 * @param mixed $package
	 * @return void
	 */
	public function calculate_shipping( $package ) {
		$rates_option = get_option( 'wc_autoship_price_shipping_rates' );
		if ( empty( $rates_option ) ) {
			return;
		}
		
		$subtotal = 0.0;
		foreach ( $package['contents'] as $item ) {
			$subtotal += floatval( $item['line_total'] );
		}
		
		foreach ( $rates_option as $rate_row ) {
			if ( $subtotal >= floatval( $rate_row['min_subtotal'] ) ) {
				$rate = array(
					'id' => $this->id,
					'label' => $rate_row['label'],
					'cost' => $rate_row['cost'],
					'calc_tax' => ( $this->is_taxable() ) ? 'per_order' : ''
				);
				$this->add_rate( $rate );
				break;
			}
		}
	}
	
	/**
	 * is_available function.
	 *
	 * @param array $package
	 * @return bool
	 */
	public function is_available( $package ) {
		return true;
	}
}