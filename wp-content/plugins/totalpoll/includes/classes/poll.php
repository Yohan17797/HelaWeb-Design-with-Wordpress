<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

if ( ! class_exists( 'TP_Poll' ) ) :

	/**
	 * Poll Class
	 *
	 * @package TotalPoll/Classes/Poll
	 * @since   3.0.0
	 */
	class TP_Poll {

		/**
		 * @var int Poll ID.
		 * @access private
		 * @since  3.0.0
		 */
		private $id;

		/**
		 * @var string Poll Unique ID
		 * @access private
		 * @since  3.0.0
		 */
		private $uniqueid = '';

		/**
		 * @var int Poll total votes.
		 * @access private
		 * @since  3.0.0
		 */
		private $votes = 0;

		/**
		 * @var string Poll Question.
		 * @access private
		 * @since  3.0.0
		 */
		private $question = null;

		/**
		 * @var array Poll Choices.
		 * @access private
		 * @since  3.0.0
		 */
		private $choices = null;

		/**
		 * @var array Poll Settings.
		 * @access private
		 * @since  3.0.0
		 */
		private $settings = null;

		/**
		 * @var array Poll limitations.
		 * @access private
		 * @since  3.0.0
		 */
		private $limitations = null;

		/**
		 * @var array Poll fields.
		 * @access private
		 * @since  3.0.0
		 */
		private $fields = null;

		/**
		 * @var object Poll request.
		 * @access private
		 * @since  3.0.0
		 */
		private $request = null;

		/**
		 * @var object Poll page.
		 * @access private
		 * @since  3.0.0
		 */
		private $page = 1;

		/**
		 * @var object Poll next page.
		 * @access private
		 * @since  3.0.0
		 */
		private $next_page = 1;


		/**
		 * @var string Skip to.
		 * @access private
		 * @since  3.0.0
		 */
		private $skip_to = null;

		/**
		 * Poll constructor.
		 *
		 * @param            $id       Poll ID.
		 * @param bool       $prefetch Prefetch question and choices.
		 *
		 * @since 3.0.0
		 */
		public function __construct( $id, $prefetch = false ) {
			// Set the ID
			$this->id = (int) $id;

			// Prefetch properties
			if ( $prefetch ):
				$this->question();
				$this->choices();
			endif;

			$extensions = $this->settings( 'extensions' );
			if ( ! empty( $extensions ) ):
				foreach ( $extensions as $extension ):
					TotalPoll::module( 'extension', $extension, $this );
				endforeach;
			endif;
		}

		/**
		 * Get poll question.
		 *
		 * @since 3.0.0
		 * @return mixed|string Poll question.
		 */
		public function question() {
			// Check the cache
			if ( $this->question === null ):
				$this->question = get_post_meta( $this->id, 'question', true );
			endif;

			return $this->question;
		}

		/**
		 * Count choices.
		 *
		 * @since 3.0.0
		 * @return int Choices count.
		 */
		public function choices_count() {
			return (int) get_post_meta( $this->id, 'choices', true );
		}

		/**
		 * Total votes.
		 *
		 * @since 3.0.0
		 * @return int Choices total votes.
		 */
		public function choices_votes() {
			return (int) get_post_meta( $this->id, 'votes', true );
		}

		/**
		 * Get poll choices.
		 *
		 * @param bool $purge        Purge cached choices
		 * @param bool $visible_only Retrieve only visible choices.
		 *
		 * @since 3.0.0
		 * @return array Poll choices.
		 */
		public function choices( $purge = false, $visible_only = true ) {

			$is_wp_admin = ( is_admin() === true && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) );
			if ( $this->choices === null || $purge !== false ):
				$order_enabled = ! $is_wp_admin && ( $this->settings( 'choices', 'order', 'enabled' ) || $this->settings( 'results', 'order', 'enabled' ) );

				$this->choices        = array();
				$choices_count        = $this->choices_count();
				$choices_per_page     = $is_wp_admin ? 0 : (int) $this->settings( 'choices', 'pagination', 'per_page' );
				$choices_offset_start = ( $order_enabled || $choices_per_page === 0 ) ? 0 : $choices_per_page * ( $this->page - 1 );
				$choices_offset_end   = ( $order_enabled || $choices_per_page === 0 || ( $choices_per_page * $this->page ) > $choices_count )
					? $choices_count
					: ( $choices_per_page * $this->page );

				$visible_only = ( $visible_only === true ) && ( is_admin() === false );

				if ( $choices_count > 0 ):
					for ( ; $choices_offset_start < $choices_offset_end; $choices_offset_start ++ ):

						// Get choice content.
						$choice_content = (array) get_post_meta( $this->id, "choice_{$choices_offset_start}_content", true );

						// Check visibility
						if ( $visible_only === true && empty( $choice_content['visible'] ) ):
							// Expand the loop until a visible choice is present.
							$choices_offset_end += ( $choices_count >= $choices_offset_end + 1 ) ? 1 : 0;

							continue;
						endif;

						// Checked
						$choice_checked = $this->request !== null && in_array( $choices_offset_start, $this->request->choices );

						// Get choice votes.
						$choice_votes = (int) get_post_meta( $this->id, "choice_{$choices_offset_start}_votes", true );

						// Append to choices array.
						$this->choices[ $choices_offset_start ] = array(
							'index'   => $choices_offset_start,
							'votes'   => $choice_votes,
							'checked' => $choice_checked,
							'content' => $choice_content,
						);

						// Add date attribute when missed.
						if ( empty( $this->choices[ $choices_offset_start ]['content']['date'] ) === true ):
							$this->choices[ $choices_offset_start ]['content']['date'] = current_time( 'timestamp' );
						endif;

						// Accumulate votes.
						$this->votes = $this->choices_votes();

					endfor;

					// Calc votes percentage.
					$this->percentages();
				endif;

				// Next page.
				if ( $order_enabled ):
					$choices_offset_end = ( $choices_per_page === 0 ) ? $choices_count : ( $choices_per_page * $this->page );
				endif;

				$this->next_page = $choices_offset_end < $choices_count ? $this->page + 1 : $this->page;
			endif;

			if ( ! $is_wp_admin ):

				// Order
				if ( $this->skip_to === 'results' ):

					// Other
					if ( empty( $this->choices['other']['content']['type'] ) === false && $this->choices['other']['content']['type'] === 'other' ):
						unset( $this->choices['other'] );
					endif;

					if ( $this->settings( 'results', 'order', 'enabled' ) ):
						$this->order( $this->settings( 'results', 'order' ) );
					endif;

				else:
					if ( $this->settings( 'choices', 'order', 'enabled' ) ):
						$this->order( $this->settings( 'choices', 'order' ) );
					endif;
				endif;

				if ( isset( $order_enabled ) && isset( $choices_per_page ) && $choices_per_page !== 0 && $order_enabled === true ):
					$choices_offset_start = ( $choices_per_page === 0 ) ? 0 : $choices_per_page * ( $this->page - 1 );
					$this->choices        = array_slice( $this->choices, $choices_offset_start, $choices_per_page );
				endif;

			endif;

			if ( $this->skip_to === 'vote' && isset( $this->choices['other'] ) === false ):
				// Other
				if ( $this->settings( 'choices', 'other', 'enabled' ) ):
					$this->choices['other'] = array(
						'index'   => count( $this->choices ) - 1,
						'votes'   => 0,
						'votes%'  => 0,
						'content' => array(
							'date'  => current_time( 'timestamp' ),
							'type'  => 'other',
							'label' => __( 'Other', TP_TD ),
						),
					);
				endif;
			endif;

			return $this->choices;
		}

		/**
		 * Get a section of settings or a specified key of a section.
		 *
		 * @param            $section
		 * @param mixed      $args ...Args list
		 *
		 * @since 3.0.0
		 * @return mixed|bool Value of the settings
		 */
		public function settings( $section, $args = false ) {
			// Section.
			if ( ! isset( $this->settings[ $section ] ) ):
				$this->settings[ $section ] = get_post_meta( $this->id, "settings_$section", true );
			endif;

			// Deep selection.
			if ( $this->settings[ $section ] !== false && $args !== false ):
				$paths = func_get_args();
				unset( $paths[0] );

				return TotalPoll::instance( 'helpers' )->pathfinder( $this->settings[ $section ], $paths );
			endif;

			return $this->settings[ $section ];
		}

		/**
		 * Get Limitations instance.
		 *
		 * @since 3.0.0
		 * @return object Limitations instance.
		 */
		public function limitations() {
			if ( $this->limitations === null ):

				if ( $this->choices === null ):
					$this->choices();
				endif;

				$this->limitations = TotalPoll::instance( 'limitations', array( $this, $this->request ) );
			endif;

			return $this->limitations;
		}

		/**
		 * Get Fields instance.
		 *
		 * @since 3.0.0
		 * @return object Fields instance.
		 */
		public function fields() {
			if ( $this->fields === null ):
				$this->fields = TotalPoll::instance( 'fields', array( $this, $this->request ) );
			endif;

			return $this->fields;
		}

		/**
		 * Render the poll.
		 *
		 * @param string $fragment Fragment to render.
		 *
		 * @since 3.0.0
		 * @return string Rendered Poll.
		 */
		public function render( $fragment = 'vote' ) {

			/**
			 * Before render.
			 *
			 * @param string $fragment Current fragment.
			 * @param        object    Poll object.
			 *
			 * @since  3.0.0
			 * @action totalpoll/actions/poll/render/before
			 */
			do_action( 'totalpoll/actions/poll/render/before', $fragment, $this );

			// Run limitations.
			$this->limitations()->run();
			// Run fields validations.
			$this->fields()->run();

			// Extra attributes
			$extra_attributes = '';

			// Skip to another fragment
			if ( ! empty( $this->skip_to ) ):
				$fragment = $this->skip_to;
			else:

				// Check request
				if ( $this->request === null ):

					// Display before voting screen if there is one.
					if ( $this->settings( 'screens', 'before_vote', 'enabled' ) !== false ):
						$fragment = 'before_vote';
					endif;

				else:

					// Check if there are limitations/validations errors.
					if ( ! empty( $this->limitations()->bag->errors ) || ! empty( $this->fields()->bag->errors ) ):
						$fragment = 'vote';
					else:

						if ( $this->request->type() === 'vote' ) :
							if ( $this->settings( 'screens', 'after_vote', 'enabled' ) !== false ):
								$fragment = 'after_vote';
							else:
								$fragment = 'results';
							endif;
						endif;

					endif;

					if ( $this->request->type() === 'results' ):
						$fragment = 'results';
					endif;

				endif;

				$this->skip_to = $fragment;
			endif;

			if ( $this->skip_to === 'results' && $this->settings( 'results', 'hide', 'enabled' ) !== false ):
				$end_date_reached = $this->settings( 'results', 'hide', 'until', 'end_date' ) && isset( $this->limitations()->bag->errors['end_date'] );
				$quota_reached    = $this->settings( 'results', 'hide', 'until', 'quota' ) && isset( $this->limitations()->bag->errors['quota'] );

				if ( ! $end_date_reached && ! $quota_reached ):
					$fragment = 'hidden_results';
				endif;
			endif;


			$template        = $this->settings( 'design', 'template', 'name' );
			$template_object = TotalPoll::module( 'template', empty( $template ) ? 'default' : $template, $this );

			if ( ! defined( 'DOING_AJAX' ) || DOING_AJAX === false ):
				wp_enqueue_script( 'totalpoll', TP_URL . 'assets/js' . ( WP_DEBUG ? '' : '/min' ) . '/front.js', array( 'jquery' ), ( WP_DEBUG ? time() : TP_VERSION ) );
				$js_settings = array(
					'AJAX'        => admin_url( 'admin-ajax.php' ),
					'AJAX_ACTION' => 'tp_action',
					'VERSION'     => TP_VERSION,

					'settings' => array(
						'limitations' => array(
							'selection_maximum' => (int) $this->settings( 'limitations', 'selection', 'maximum' ),
							'captcha'           => array(
								'enabled' => (bool) $this->settings( 'limitations', 'captcha', 'enabled' ),
								'sitekey' => get_option( '_tp_options_captcha_site_key' ),
								'hl'      => current( explode( '_', get_locale() ) ),
							),
						),
					),
				);
				wp_localize_script(
					'totalpoll',
					'TotalPoll',
					$js_settings
				);

				if ( $js_settings['settings']['limitations']['captcha']['enabled'] ):
					wp_enqueue_script( 'grecaptcha', "//www.google.com/recaptcha/api.js?onload=reCaptchaReady&render=explicit&hl={$js_settings['settings']['limitations']['captcha']['hl']}",
						array( 'totalpoll' ) );
				endif;

				$template_object->assets();

				/**
				 * Enqueue assets.
				 *
				 * @param string $fragment Current fragment.
				 * @param        object    Poll object.
				 *
				 * @since  3.0.0
				 * @action totalpoll/actions/poll/render/assets
				 */
				do_action( 'totalpoll/actions/poll/render/assets', $fragment, $this );
			endif;

			$extra_attributes .= sprintf( ' %s="%s"', 'data-id', $this->id );
			$extra_attributes .= sprintf( ' %s="%s"', 'data-max-selection', $this->settings( 'limitations', 'selection', 'maximum' ) );
			$extra_attributes .= sprintf( ' %s="%s"', 'data-template', $template );

			$html = $template_object->render( $fragment, empty( $this->request->is_ajax ) ? true : false, $extra_attributes );

			/**
			 * Vote indexes.
			 *
			 * @param string $html     Rendered poll.
			 * @param string $fragment Current fragment.
			 * @param        object    Poll object.
			 *
			 * @since  3.0.0
			 * @filter totalpoll/filters/poll/render/content
			 */
			return apply_filters( 'totalpoll/filters/poll/render/content', $html, $fragment, $this );
		}

		/**
		 * Insert new choice in poll choices.
		 *
		 * @param array      $choice Choice array.
		 * @param bool|false $visible_after_insertion
		 *
		 * @since 3.0.0
		 * @return bool True on success, false on failure
		 */
		public function insert_choice( $choice, $visible_after_insertion = false ) {

			// Check choice
			if ( ! empty( $choice ) ):

				// Count choices
				$choices_count = $this->choices_count();

				foreach ( $choice as $key => $value ):

					// Compose a key
					$key = "choice_{$choices_count}_{$key}";
					// Create/update meta data
					if ( update_post_meta( $this->id, $key, $value ) === false ):
						return false; // If one fails, return false
					endif;

				endforeach;

				// Update count
				update_post_meta( $this->id, 'choices', $choices_count + 1 );

				// Refresh choices
				$choice['index']  = $choices_count;
				$choice['votes%'] = 0;
				if ( $visible_after_insertion === true && $this->choices !== null ):
					$choice['content']['visible'] = true;
					$this->choices['other']       = $choice;

					update_post_meta( $this->id, 'votes', $this->votes + (int) $choice['votes'] );
				endif;

				// Successful insertion
				return true;
			endif;

			// Failed
			return false;
		}

		/**
		 * Vote.
		 *
		 * @param array $indexes Indexes of choices.
		 *
		 * @since 3.0.0
		 * @return bool True when accepted, false when refused.
		 */
		public function vote( $indexes ) {

			// Check indexes
			if ( ! empty( $indexes ) ):

				// Count choices
				$choices_count = $this->choices_count();
				$indexes       = array_unique( $indexes );

				/**
				 * Vote indexes.
				 *
				 * @param array  $indexes Choices indexes.
				 * @param object $poll    Poll object.
				 *
				 * @since  3.0.0
				 * @filter totalpoll/filters/poll/vote/indexes
				 */
				$indexes = apply_filters( 'totalpoll/filters/poll/vote/indexes', $indexes, $this );

				$translations_ids = array( $this->id );
				$original_id      = $this->id;

				// WPML compatibility
				if ( ! empty( $GLOBALS['sitepress'] ) ):
					$translations = $GLOBALS['sitepress']->get_element_translations( $GLOBALS['sitepress']->get_element_trid( $this->id, 'post_poll' ) );
					foreach ( $translations as $translation ):
						if ( $translation->element_id != $this->id ):
							$translations_ids[] = $translation->element_id;
						endif;
						if ( $translation->original == 1 ):
							$original_id = $translation->element_id;
							$this->votes = (int) get_post_meta( $original_id, 'votes', true );
						endif;
					endforeach;
				endif;

				// Polylang compatibility
				if ( ! empty( $GLOBALS['polylang'] ) ):
					$translations = pll_get_post_translations( $this->id );
					foreach ( $translations as $code => $translation ):
						if ( $translation != $this->id ):
							$translations_ids[] = $translation;
						endif;
						if ( pll_default_language() == $code ):
							$original_id = $translation;
							$this->votes = (int) get_post_meta( $original_id, 'votes', true );
						endif;
					endforeach;
				endif;

				foreach ( $indexes as $index => $choice ):

					// Other (user submission)
					if ( $index === 'other' ):
						$choice['label'] = str_replace( array( ' ', '.', ',', '-', '_', '/', '\\' ), '', empty( $choice['label'] ) ? '' : $choice['label'] );
					endif;

					if ( $index === 'other' && ! empty( $choice['label'] ) && $this->settings( 'choices', 'other', 'enabled' ) ):

						$this->insert_choice(
							array(
								'votes'   => 1,
								'content' => array(
									'type'    => 'text',
									'date'    => current_time( 'timestamp' ),
									'visible' => ! $this->settings( 'choices', 'other', 'moderation' ),
									'label'   => sanitize_text_field( $choice['label'] ),
								),
							),
							true
						);

						continue;
					endif;

					// Regular votes

					$choice = (int) $choice;

					if ( $choice >= 0 && $choice < $choices_count ):

						$votes = (int) get_post_meta( $original_id, "choice_{$choice}_votes", true );

						if ( empty( $this->choices[ $choice ] ) === false ):
							$this->choices[ $choice ]['votes'] = $votes + 1;
							$this->votes += 1;
						endif;

						foreach ( $translations_ids as $id ):

							if ( update_post_meta( $id, "choice_{$choice}_votes", $votes + 1 ) === false ):
								return false; // If one fails, return false
							endif;

						endforeach;


					endif;

					foreach ( $translations_ids as $id ):
						update_post_meta( $id, 'votes', $this->votes );
					endforeach;

				endforeach;

				if ( ! empty( $this->choices ) ):
					$this->percentages();
				endif;

				return true; // Successful update

			endif;

			return false; // Nothing to update
		}


		/**
		 * Get/set request object.
		 *
		 * @param bool|false $request
		 *
		 * @return bool|object Request object, false otherwise.
		 */
		public function requested_by( $request = false ) {
			if ( $request !== false && $request instanceof TotalPoll::$classes['request']['class'] ):
				$this->request = $request;
			endif;

			return $this->request;
		}

		/**
		 * Skip to another fragment.
		 *
		 * @param null $fragment Fragment to show (vote, results ...)
		 *
		 * @since 3.0.0
		 * @return string Fragment.
		 */
		public function skip_to( $fragment = null ) {
			return $this->skip_to = $fragment;
		}

		/**
		 * Get/set current page.
		 *
		 * @param false|int $page Page.
		 *
		 * @since 3.0.0
		 * @return int Page
		 */
		public function page( $page = false ) {
			$page = ( $page === false ) ? $this->page : absint( $page );

			if ( $this->page !== $page ):
				$this->choices = null;
			endif;

			return $this->page = $page;
		}

		/**
		 * Check if there is another page.
		 *
		 * @since 3.0.0
		 * @return bool True when has next page, false otherwise.
		 */
		public function has_next_page() {
			return $this->next_page > $this->page;
		}

		/**
		 * Check if there is a previous page.
		 *
		 * @since 3.0.0
		 * @return bool True when has next page, false otherwise.
		 */
		public function has_previous_page() {
			return $this->page > 1;
		}

		/**
		 * Calculate percentages.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		private function percentages() {
			foreach ( $this->choices as $index => $choice ):
				$percentage                        = $choice['votes'] === 0 || $this->votes === 0 ? 0 : ( $choice['votes'] / $this->votes ) * 100;
				$this->choices[ $index ]['votes%'] = empty( $choice['content']['visible'] ) ? 0 : round( $percentage, 2 );
			endforeach;
		}

		/**
		 * Order choices
		 *
		 * @param array $settings Settings array (by and direction).
		 *
		 * @since 3.0.0
		 * @return void
		 */
		private function order( $settings ) {
			usort( $this->choices, array( $this, "_sort_by_{$settings['by']}" ) );

			// Order direction
			if ( ! empty( $settings['direction'] ) ):
				if ( $settings['direction'] === 'desc' ):
					krsort( $this->choices );
				endif;
			endif;
		}

		/**
		 * Sort by votes callback.
		 *
		 * @param $current Current element
		 * @param $next    Next element
		 *
		 * @since 3.0.0
		 * @return int
		 */
		private function _sort_by_votes( $current, $next ) {
			if ( $current['votes'] === $next['votes'] ):
				return 0;
			elseif ( $current['votes'] < $next['votes'] ):
				return - 1;
			else:
				return 1;
			endif;
		}

		/**
		 * Sort by label callback.
		 *
		 * @param $current Current element
		 * @param $next    Next element
		 *
		 * @since 3.0.0
		 * @return int
		 */
		private function _sort_by_label( $current, $next ) {
			return strcasecmp( $current['content']['label'], $next['content']['label'] );
		}

		/**
		 * Sort by date callback.
		 *
		 * @param $current Current element
		 * @param $next    Next element
		 *
		 * @since 3.0.0
		 * @return int
		 */
		private function _sort_by_date( $current, $next ) {
			if ( (int) $current['content']['date'] === (int) $next['content']['date'] ):
				return 0;
			elseif ( (int) $current['content']['date'] < (int) $next['content']['date'] ):
				return - 1;
			else:
				return 1;
			endif;
		}

		/**
		 * Sort randomly callback.
		 *
		 * @param $current Current element
		 * @param $next    Next element
		 *
		 * @since 3.0.0
		 * @return int
		 */
		private function _sort_by_random( $current, $next ) {
			return mt_rand( - 1, 1 );
		}

		/**
		 * Getter
		 *
		 * @param string $name Property name.
		 * @param array  $args Args.
		 *
		 * @since 3.0.0
		 * @return mixed Property, false when the property is undefined.
		 */
		public function __call( $name, $args ) {
			return isset( $this->{$name} ) ? $this->{$name} : false;
		}

		/**
		 * To string.
		 *
		 * @since 3.0.0
		 * @return string Poll.
		 */
		public function __toString() {
			return $this->render();
		}

	}


endif;
