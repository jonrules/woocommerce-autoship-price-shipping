<h4>Shipping Rates</h4>

<table class="wc-autoship-price-shipping-rates-table">
	<thead>
		<tr>
			<th><?php echo __( 'Min Order Subtotal', 'wc-autoship-price-shipping' ); ?></th>
			<th><?php echo __( 'Shipping Cost', 'wc-autoship-price-shipping' ); ?></th>
			<th><?php echo __( 'Label', 'wc-autoship-price-shipping' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php for ( $row = 0; $row < 10; $row++ ): ?>
			<?php $min_subtotal = isset( $rates[ $row ]['min_subtotal'] ) ? number_format( $rates[ $row ]['min_subtotal'], 2 ) : ''; ?>
			<?php $cost = isset( $rates[ $row ]['cost'] ) ? number_format( $rates[ $row ]['cost'], 2 ) : ''; ?>
			<?php $label = isset( $rates[ $row ]['label'] ) ? $rates[ $row ]['label'] : ''; ?>
			<tr>
				<td><input type="text" name="wc_autoship_price_shipping_rates[<?php echo $row; ?>][min_subtotal]" class="wc-autoship-price-shipping-min-subtotal" value="<?php echo esc_attr( $min_subtotal ); ?>" placeholder="0.00" /></td>
				<td><input type="text" name="wc_autoship_price_shipping_rates[<?php echo $row; ?>][cost]" class="wc-autoship-price-shipping-cost" value="<?php echo esc_attr( $cost, 2 ); ?>" placeholder="0.00" /></td>
				<td><input type="text" name="wc_autoship_price_shipping_rates[<?php echo $row; ?>][label]" class="wc-autoship-price-shipping-label" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php echo __( 'Shipping', 'wc-autoship-price-shipping' ); ?>" /></td>
			</tr>
		<?php endfor; ?>
	</tbody>
</table>
