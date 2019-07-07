<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<tr>
	<?php foreach ( ( isset( $raw_fields ) ? $raw_fields : $this->poll->fields()->raw_fields() ) as $index => $field ): ?>
		<td scope="col" class="manage-column column <?php echo $index < 3 ? 'active' : ''; ?>" data-tp-toggleable="submission-column-<?php echo esc_attr( $field['name'] ); ?>">
			<?php

			if ( ! empty( $item[ $field['name'] ] ) ):
				echo esc_html( implode( ', ', (array) $item[ $field['name'] ] ) );
			else:
				_e( 'N/A', TP_TD );
			endif;

			?>
		</td>
	<?php endforeach; ?>
</tr>