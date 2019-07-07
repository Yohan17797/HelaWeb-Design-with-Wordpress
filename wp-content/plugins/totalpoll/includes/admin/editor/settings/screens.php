<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-screens" data-tp-tab-content="screens">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before', $screens, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][before_vote][enabled]" data-tp-toggle="screens-before-vote-advanced" <?php checked( empty( $screens['before_vote']['enabled'] ), false ); ?>>
				<?php _e( 'Before voting', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['before_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-before-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['before_vote']['content'] ) ? '' : $screens['before_vote']['content'], 'beforeVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][before_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/before-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][screens][after_vote][enabled]" data-tp-toggle="screens-after-vote-advanced" <?php checked( empty( $screens['after_vote']['enabled'] ), false ); ?>>
				<?php _e( 'After voting', TP_TD ); ?>
			</label>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after-voting', $screens, $this->poll ); ?>

	</div>

	<div class="settings-item-advanced <?php echo empty( $screens['after_vote']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="screens-after-vote-advanced">

		<div class="settings-field">
			<?php wp_editor( empty( $screens['after_vote']['content'] ) ? '' : $screens['after_vote']['content'], 'afterVoteScreen', array( 'textarea_name' => 'totalpoll[settings][screens][after_vote][content]' ) ); ?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after-voting-advanced', $screens, $this->poll ); ?>

	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/screens/after', $screens, $this->poll ); ?>

</div>