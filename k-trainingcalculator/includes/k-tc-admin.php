<?php

/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 *
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 *
 * Our theme for this list table is going to be movies.
 */

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Registration_Table extends WP_List_Table
{

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We
     * use the parent reference to set some default configs.
     ***************************************************************************/
    public function __construct()
    {
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular' => 'tc', //singular name of the listed records
            'plural' => 'tcs', //plural name of the listed records
            'ajax' => false, //does this table support ajax?
        ));

    }

    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title()
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as
     * possible.
     *
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     *
     * For more detailed insight into how columns are handled, take a look at
     * WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'rating':
            case 'director':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     *
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     *
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    public function column_title($item)
    {

        //Build row actions
        $actions = array(
            'edit' => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['ID']),
            'delete' => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID']),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/$item['title'],
            /*$2%s*/$item['ID'],
            /*$3%s*/$this->row_actions($actions)
        );
    }

    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/$this->_args['singular'], //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']//The value of the checkbox should be the record's id
        );
    }

    /** ************************************************************************
	 * Get an associative array ( id => link ) with the list
	 * of views available on this table.
	 *
	 * @since 3.1.0
	 *
	 * @return array
     **************************************************************************/
    public function get_views()
    {
        $current_url = $this->get_current_url();
        $current_url = remove_query_arg('percentage', $current_url);

        $status_links = array(
            "all" => __("<a href='" . $current_url . "'>All</a>"),
            "cat-a" => __("<a href='" . esc_url(add_query_arg(['percentage' => '10'], $current_url)) . "'>Category A</a>"),
            "cat-b" => __("<a href='" . esc_url(add_query_arg(['percentage' => '20'], $current_url)) . "'>Category B</a>"),
            "cat-c" => __("<a href='" . esc_url(add_query_arg(['percentage' => '30'], $current_url)) . "'>Category C</a>"),
        );

        return $status_links;
    }    

    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     *
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     *
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    public function get_columns()
    {
        $columns = array(
            // 'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'company' => 'Company',
            'category' => 'Category',
            'activity' => 'Activity',
            'employees' => 'Employees',
            'trainees' => 'Trainees',
            'contactperson' => 'Contact Person',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'courses' => 'Courses',
            'langs' => 'Languages',
            'locs' => 'Locations',
            'dateadded' => 'Date',
        );
        return $columns;
    }

    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle),
     * you will need to register it here. This should return an array where the
     * key is the column that needs to be sortable, and the value is db column to
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     *
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     *
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            // table_colname => array(db_colname, isPresorted),
            'company' => array('company_name', true),
            'category' => array('category', true),
            'activity' => array('activity', true),
            'employees' => array('employeecount', true),
            'trainees' => array('traineecount', true),
            'contactperson' => array('contactperson', true),
            'email' => array('email', true),
            'mobile' => array('contactmobile', true),
            'courses' => array('courses', true),
            'langs' => array('languages', true),
            'locs' => array('locations', true),
            'dateadded' => array('date_added', true),
        );
        return $sortable_columns;
    }

    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     *
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     *
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     *
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    public function get_bulk_actions()
    {
        $actions = array(
            'export' => 'Export All',
        );
        return $actions;
    }

    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     *
     * @see $this->prepare_items()
     **************************************************************************/
    public function process_bulk_action()
    {

        //Detect when a bulk action is being triggered...
        if ('export' === $this->current_action()) {
            $this->export_to_csv();
            // wp_die('Items deleted (or they would be if we had items to delete)!');
            wp_die();
        }

    }

    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     *
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    public function prepare_items()
    {
        global $wpdb; //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 8;

        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);

        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();

        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         *
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         *
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        $data = [];

        $table_name = $wpdb->prefix . "registrations";

        // echo $table_name;

        $query = "SELECT wp_registrations.id,
      wp_registrations.name,
      wp_company_activities.name_en as activity,
      wp_registrations.employeecount,
      wp_registrations.traineecount,
      wp_registrations.contactperson,
      wp_registrations.email,
      wp_registrations.contactmobile,
      wp_registrations.courses,
      wp_registrations.languages,
      wp_registrations.locations,
      wp_registrations.date_added,
      CASE PERCENTAGE WHEN 10 THEN 'A' WHEN 20 THEN 'B' ELSE 'C' END AS category
      FROM wp_registrations
      inner join wp_company_activities on wp_registrations.activity = wp_company_activities.id";

        $where_args = [];
        
        if (isset($_GET['percentage'])) {
            array_push($where_args, ['percentage' => $_GET['percentage']]);
        }

        $wheres = " WHERE ";
        if (count($where_args) > 0) {
            foreach ($where_args as $array) {
                $key = key($array);
                $wheres .= $key . ' = "' . $array[$key] . '"';
            }

            $query .= " $wheres";
        }

        /**
         * Search function
         */
        if (isset($_GET['s'])) {
            $search = $_GET['s'];

            $search_query .= " (
                name LIKE '%{$search}%' 
                OR wp_company_activities.name_en LIKE '%{$search}%' 
                OR employeecount LIKE '%{$search}%' 
                OR traineecount LIKE '%{$search}%' 
                OR contactperson LIKE '%{$search}%' 
                OR email LIKE '%{$search}%'
                OR contactmobile LIKE '%{$search}%'
                OR courses LIKE '%{$search}%'
                OR languages LIKE '%{$search}%'
                OR locations LIKE '%{$search}%')";

            if (count($where_args) > 0) {
                $query .= " AND " . $search_query;
            } else {
                $query .= $wheres . $search_query;
            }
        }

        $results = $wpdb->get_results($query);

        foreach ($results as $key => $row) {
            // print_r($row->id);
            $aRow = array(
                'id' => $row->id,
                'company_name' => $row->name,
                'category' => $row->category,
                'activity' => $row->activity,
                'employeecount' => $row->employeecount,
                'traineecount' => $row->traineecount,
                'contactperson' => $row->contactperson,
                'email' => $row->email,
                'contactmobile' => $row->contactmobile,
                'courses' => $row->courses,
                'languages' => $row->languages,
                'locations' => $row->locations,
                'date_added' => $row->date_added,
            );

            array_push($data, $aRow);
        }

        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example
         * package slightly different than one you might build on your own. In
         * this example, we'll be using array manipulation to sort and paginate
         * our data. In a real-world implementation, you will probably want to
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        // $data = $this->example_data;

        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         *
         * In a real-world situation involving a database, you would probably want
         * to handle sorting by passing the 'orderby' and 'order' values directly
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a, $b)
        {
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order === 'asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');

        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        $total_items = count($data);

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to
         */
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $data;

        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page), //WE have to calculate the total number of pages
        ));
    }

    public function display_rows()
    {
        $records = $this->items;

        $columns = $this->get_columns();

        foreach ($records as $rec) {

            echo '<tr id="record_' . $rec->id . '">';

            foreach ($columns as $column_name => $column_display_name) {
                $class = "class='$column_name column-$column_name'";
                $style = "";
                $attributes = $class . $style;

                switch ($column_name) {
                    case "id":echo '<td ' . $attributes . '>' . stripslashes($rec->id) . '</td>';
                        break;
                    case "company":echo '<td ' . $attributes . '>' . stripslashes($rec['company_name']) . '</td>';
                        break;
                    case "category":echo '<td ' . $attributes . '>' . stripslashes($rec['category']) . '</td>';
                        break;
                    case "activity":echo '<td ' . $attributes . '>' . stripslashes($rec['activity']) . '</td>';
                        break;
                    case "employees":echo '<td ' . $attributes . '>' . intval($rec['employeecount']) . '</td>';
                        break;
                    case "trainees":echo '<td ' . $attributes . '>' . intval($rec['traineecount']) . '</td>';
                        break;
                    case "courses":echo '<td ' . $attributes . '>' . $rec['courses'] . '</td>';
                        break;
                    case "langs":echo '<td ' . $attributes . '>' . $rec['languages'] . '</td>';
                        break;
                    case "locs":echo '<td ' . $attributes . '>' . $rec['locations'] . '</td>';
                        break;
                    case "mobile":echo '<td ' . $attributes . '><a href="tel:' . $rec['contactmobile'] . '">' . $rec['contactmobile'] . '</a></td>';
                        break;
                    case "dateadded":echo '<td ' . $attributes . '>' . $rec['date_added'] . '</td>';
                        break;
                    case "contactperson":echo '<td ' . $attributes . '>' . $rec['contactperson'] . '</td>';
                        break;
                    case "email":echo '<td ' . $attributes . '><a href="mailto:' . $rec['email'] . '">' . $rec['email'] . '</a></td>';
                        break;
                    case "contactmobile":echo '<td ' . $attributes . '>' . $rec['contactmobile'] . '</td>';
                        break;
                }
            }

            echo '</tr>';
        }
    }

    public function export_to_csv()
    {
        $columns = $this->get_columns();
        $records = $this->items;

        $filename = $file . "_" . date("Y-m-d_H-i", time());
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: csv" . date("Y-m-d") . ".csv");
        header("Content-disposition: filename=" . $filename . ".csv");

        exit;
    }
}

/* add registration page on admin */
function registration_menu()
{
    add_menu_page('Registrations', 'Registrations', 'manage_options', 'registrations/admin.php', 'registration_page', 'dashicons-heart', 6);
}
add_action('admin_menu', 'registration_menu');

function registration_page()
{

    //Create an instance of our package class...
    $registrations_table = new Registration_Table();
    //Fetch, prepare, sort, and filter our data...
    $registrations_table->prepare_items();

    ?>
    <div class="wrap">

        <div id="icon-users" class="icon32"><br/></div>
        <h2>Registrations</h2>
        <?php $registrations_table->views();?>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="registrations-form" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <?php $registrations_table->search_box('Find' , 'registration-find');?>
            <!-- Now we can render the completed list table -->
            <?php $registrations_table->display()?>
        </form>
    </div>
    <?php
}