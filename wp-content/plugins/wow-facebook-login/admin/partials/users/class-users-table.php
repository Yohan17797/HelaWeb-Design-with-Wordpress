<?php if ( ! defined( 'ABSPATH' ) ) exit;
	
	// WP_List_Table is not loaded automatically so we need to load it in our application
	if( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}
	/**
		* Create a new table class that will extend the WP_List_Table
	*/
	class Wow_Social_Users extends WP_List_Table{
		
		/**
			* Number of items per page
			*
			* @var int
			* @since 1.5
		*/
		public $per_page = 30;	
		
		private $slug;
		
		public function __construct($slug) {	
			$this->slug = $slug;
			// Set parent defaults			
			parent::__construct( array(
			'singular' => 'User',
			'plural'   => 'Users',
			'ajax'     => false,
			) );			
			$this->process_bulk_action();			
		}		
		
		public function search_box( $text, $input_id ) {
			$input_id = $input_id . '-search-input';			
			if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
			if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		?>
		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
			<input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
			<?php submit_button( $text, 'button', false, false, array('ID' => 'search-submit') ); ?>
		</p>
		<?php
		}
		
		/**
			* Define what data to show on each column of the table
			*
			* @param  Array $item        Data
			* @param  String $column_name - Current column name
			*
			* @return Mixed
		*/
		public function column_default( $item, $column_name )
		{
			if ($column_name === 'email'){
				$value = '<a href="' .
				admin_url( '/user-edit.php?user_id=' . urlencode( $item['ID'] )
				) . '">' . esc_html( $item['email'] ) . '</a>';			
			}						
			else {
				$value = $item[ $column_name ];
			}	
			
			return $value;
			
		}
		/**
			* Retrieve the table columns
			*
			* @access public
			* @since 1.0
			* @return array $columns Array of all the list table columns
		*/
		
		public function column_email( $item ) {		
			$email      = ! empty( $item['email'] ) ? $item['email'] : '<em>no email</em>';			
			$view_url    = admin_url( '/user-edit.php?user_id=' . urlencode( $item['ID'] ) ) ;			
			$delete_url  = admin_url( '/admin.php?page='.$this->slug.'&tab=users&info=delete&id='. urlencode( $item['ID'] ) ) ;
			$delete_url = wp_nonce_url( $delete_url, 'wow_href_nonce' );
			
			$actions     = array(			
			'view'   => '<a href="' . $view_url . '">View</a>',
			'delete'   => '<a href="' . $delete_url . '">Delete</a>',
			);
			return '<a href="' . esc_url( $view_url ) . '">' . $email . '</a>'  . $this->row_actions( $actions );
		}		
		
		/**
			* Override the parent columns method. Defines the columns to use in your listing table
			*
			* @return Array
		*/
		public function get_columns()
		{
			$columns = array(  
			'cb'           => '<input type="checkbox" />',            
            'email'        => 'Email', 			
			'first_name'   => 'First Name',
			'last_name'    => 'Last Name',	
			'date'         => 'Date',
			'link'         => 'Profile',
			);
			return $columns;
			
		}
		
		/**
			* Define the sortable columns
			*
			* @return Array
		*/
		public function get_sortable_columns()
		{
			return array(
			'ID'          => array( 'ID', false ),						
			'date' => array( 'date',true ),						
			);					
		}
		
		/**
			* Gets the name of the primary column.
			*
			* @since 1.0
			* @access protected
			*
			* @return string Name of the primary column.
		*/
		protected function get_primary_column_name() {
			return 'date';
		}
		
		/**
			* Retrieves the search query string
			*
			* @access public
			* @since 1.0
			* @return mixed string If search is present, false otherwise
		*/
		public function get_search() {
			return ! empty( $_POST['s'] ) ? urldecode( trim( $_POST['s'] ) ) : false;
		}
		
		
		/**
			* Retrieve the current page number
			*
			* @access public
			* @since 1.5
			* @return int Current page number
		*/
		public function get_paged() {
			return isset( $_GET['paged'] ) ? absint( $_GET['paged'] ) : 1;
		}
		
		/**
			* Prepare the items for the table to process
			*
			* @return Void
		*/
		public function prepare_items()
		{
			$columns = $this->get_columns();
			$hidden = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();		
			$data = $this->table_data();
			$perPage = 30;
			$currentPage = $this->get_pagenum();
			if ($data){
				usort( $data, array( &$this, 'sort_data' ) );
				$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
			}        			
			$totalItems = $this->users_count();			
			$this->_column_headers = array($columns, $hidden, $sortable);
			$this->items = $data;
			$this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage,
			'total_pages' => ceil( $totalItems / $perPage ),
			) );
		}
		
		/**
			* Define which columns are hidden
			*
			* @return Array
		*/
		public function get_hidden_columns()
		{
			return array();
		}
		
		/**
			* Render the checkbox column
			*
			* @access public
			* @since 1.0
			* @param array $item Contains all the data for the checkbox column
			* @return string Displays a checkbox
		*/
		public function column_cb( $item ) {
			return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			'ID',
			$item['ID']
			);
		}
		
		/**
			* Get the table data
			*
			* @return Array
		*/
		private function table_data()
		{
			global $wpdb;
			$data    = array();
			$paged   = $this->get_paged();
			$offset  = $this->per_page * ( $paged - 1 );
			$search  = $this->get_search();			
			$order   = isset( $_GET['order'] )   ? sanitize_text_field( $_GET['order'] )   : 'DESC';
			$orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'ID';			
			$args    = array(
			'number'  => $this->per_page,
			'offset'  => $offset,
			'order'   => $order,
			'orderby' => $orderby,
			'search'  => $search
			);			
			
			$table = $wpdb->prefix . "wow_social_users";
			$customers = $wpdb->get_results("SELECT * FROM " . $table . " WHERE type='fb' order by id desc");			
			if ($customers) {				
				foreach ($customers as $key => $value) {
					$udata = get_userdata( $value->ID );
					if($udata == false){
						$registered = '';
					}
					else{
						$registered = $udata->user_registered;
					}					
					$data[] = array(
					'ID' => $value->ID,	
					'email'  => $value->email,
					'first_name' => $value->first_name,
					'last_name' => $value->last_name,
					'link' => '<a href="'.$value->link.'" target="_blank">link</a>',
					'date' => $registered,					
					);				
				}	
			}
			return $data;	
		}
		
		public function users_count() {
			$count = count(get_users());
			return $count;
		}
		
		/**
			* Allows you to sort the data by the variables set in the $_GET
			*
			* @return Mixed
		*/
		private function sort_data( $a, $b )
		{
			// Set defaults
			$orderby = 'date';
			$order = 'desc';
			// If orderby is set, use this as the sort column
			if(!empty($_GET['orderby']))
			{
				$orderby = $_GET['orderby'];
			}
			// If order is set use this as the order
			if(!empty($_GET['order']))
			{
				$order = $_GET['order'];
			}
			$result = strcmp( $a[$orderby], $b[$orderby] );
			if($order === 'asc')
			{
				return $result;
			}
			return -$result;
		}	
		
		/**
			* Retrieve the bulk actions
			*
			* @access public
			* @since 1.4
			* @return array $actions Array of the bulk actions
		*/
		public function get_bulk_actions() {
			$actions = array(			
			'ua-delete' => 'Delate',
			);
			
			return $actions;
		}
		
		/**
			* Process the bulk actions
			*
			* @access public
			* @since 1.4
			* @return void
		*/
		public function process_bulk_action() {
			$ids    = isset( $_POST['ID'] ) ? $_POST['ID'] : false;
			$action = $this->current_action();			
			if ( ! is_array( $ids ) )
			$ids = array( $ids );			
			if( empty( $action ) )
			return;			
			global $wpdb;
			$table = $wpdb->prefix . "wow_social_users";
			foreach ( $ids as $id ) {
				if ( 'ua-delete' === $this->current_action() ) {					
					$wpdb->query("delete from " . $table . " where id=" . $id);
					wp_delete_user( $id );									
				}
			}
			
		}
		
	}
