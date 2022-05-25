<?php
/**
 * The Active View
 */
function the_active_view($view){
    if(!empty($_GET['view'])){
        if($_GET['view'] == $view)
            echo 'active';
    }else{
        return null;
    }
}

/**
 * The Success Message
 */
function the_success_message(){

    // Success Message
    if(strpos($_SERVER['REQUEST_URI'], 'success'))
        echo '<div class="alert alert-success" role="alert">Update was successful!</div>';

}

/**
 * The Page Type Badge
 */
function the_page_type_badge($page_type){
    
    // Every letter is uppercase so we have to create
    // conditions for "WordPress" or "XML" or any other
    // name that requires unique casing.
    $page_type = str_replace('_', ' ', strtoupper($page_type));
    echo '<span class="badge bg-light text-dark">'
        .$page_type.
        '<span class="visually-hidden"> Page Type</span></span>';

}

/**
 * The Integration Status Badge
 */
function the_integration_status_badge($integration_status){

    // Set badge
    // doesn't include 'planned' 'cuz the button says that.
    if($integration_status == 'Disabled'){
        $badge_class = 'bg-secondary';
        $badge_text = 'Disabled';
        echo '<span class="badge '.$badge_class.'">'.$badge_text.'<span class="visually-hidden"> Integration Status</span></span>';
    }elseif($integration_status == 'Active'){
        $badge_class = 'bg-success';
        $badge_text = 'Active';
        echo '<span class="badge '.$badge_class.'">'.$badge_text.'<span class="visually-hidden"> Integration Status</span></span>';
    }else{
        return false;
    }

}

/**
 * The Integration Activation Button
 */
function the_integration_activation_button($integration_uri, $integration_status){

    // Set button.
    if($integration_status == 'Planned'){
        $button_class = 'btn btn-outline-secondary disabled';
        $button_text = 'Coming Soon';
    }elseif($integration_status == 'Disabled'){
        $button_class = 'btn-primary';
        $button_text = 'Activate';
    }elseif($integration_status == 'Active'){
        $button_class = 'btn btn-outline-danger';
        $button_text = 'Disable';
    }else{
        $button_class = NULL;
        $button_text = NULL;
    }
    echo '<a href="actions/toggle_integration_status.php?uri='.$integration_uri.'&old_status='.$integration_status.'" class="btn '.$button_class.'">'.$button_text.'</a>';

}

/**
 * The Integration Settings Button
 */
function the_integration_settings_button($integration_uri, $integration_status){

    // Only show button on active inteagrations
    if($integration_status == 'Active'){
        echo '<a href="?view=integration_settings&uri='.$integration_uri.'" class="btn btn-secondary">Settings</a>';
    }else{
        return false;
    }

}

/**
 * The Scan Rows
 */
function the_scan_rows($scans){

    // Needs output buffer so HTML can be stored.
    ob_start();

        // Begin Scans
        if(count($scans) > 0 ): foreach($scans as $scan):    
        ?>

        <tr>
            <td><?php echo $scan->time;?></td>
            <td><?php echo ucwords($scan->status);?></td>
            <td>

            <?php             
            // Link to pages    
            $page_ids = unserialize($scan->pages);
            echo count($page_ids);
            ?>

            </td>
        </tr>

        <?php 
        // Fallback
        endforeach; else:
        ?>

        <tr>
            <td colspan="3">No scans found.</td>
        </tr>

        <?php 
        
        // End Scans
        endif;

    // Clean the buff.
    echo ob_get_clean();

}

/**
 * Convert Code Shortcode
 */
function covert_code_shortcode($subject){

    // Convert text between [code][/code] into styled
    // code.
    $subject = str_replace('[code]', '<pre class="rounded bg-secondary text-white p-3 mb-1"><code>', $subject);
    $subject = str_replace('[/code]', '</code></pre>', $subject);

    // [code] is converted!
    return $subject;

}

/**
 * Get Page Number
 */
function get_current_page_number(){

    // Pull page number from URL string.
    if (isset($_GET['current_page_number'])) {
        $current_page_number = $_GET['current_page_number'];
    } else {
        $current_page_number = 1;
    }

    // Return page
    return $current_page_number;

}

/**
 * The Pagination
 * Inspired by https://www.myprogrammingtutorials.com/create-pagination-with-php-and-mysql.html
 */
function the_pagination($total_pages){

    // Define page number.
    $current_page_number = get_current_page_number();

    // Defined current view
    $current_view = $_GET['view'];

    // Set active state as function so we don't have to keep
    // writing this condition.
    function get_active_state($current_page_number, $item_number){
        if($current_page_number == $item_number){ 
            return 'active'; 
        }else{
            return null;
        }
    }

    // Only show pagination for more than one page
    if($total_pages > 1):

?>

<nav aria-label="Page Navigation">
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if($current_page_number <= 1){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($current_page_number <= 1){ echo '#'; } else { echo '?view='.$current_view.'&current_page_number='.($current_page_number - 1); } ?>">Previous</a>
        </li>
        <li class="page-item  <?php echo get_active_state($current_page_number, 1)?>">
            <a class="page-link" href="?view=<?php echo $current_view;?>&current_page_number=1">1</a>
        </li>

        <?php
        // If there are more than 3 pages and we're not on page 2
        // and if there are more than 5 pages and we're not on page 3,
        // display a disabled elipses so that the user knows to click
        // 'previous'.
        if($current_page_number != 1 && ($total_pages > 3 && $current_page_number != 2) && ($total_pages > 5 && $current_page_number != 3))
            echo '<li class="page-item disabled"><a class="page-link" href="">...</a></li>';

        // If there are more than 5 pages and current page number isn't
        // first, second, or last...
        if($total_pages > 5 && $current_page_number != 1 && $current_page_number != 2 && $current_page_number != $total_pages)
            echo '<li class="page-item"><a class="page-link" href="?view='.$current_view.'&current_page_number='.($current_page_number-1).'">'.($current_page_number-1).'</a></li>';

        // If there are more than 3 pages and current page number isn't
        // first or last...
        if($total_pages > 3 && $current_page_number != 1 && $current_page_number != $total_pages)
            echo '<li class="page-item active"><a class="page-link" href="?view='.$current_view.'&current_page_number='.$current_page_number.'">'.$current_page_number.'</a></li>';

        // If there are more than 5 pages and current page is the first or second..
        if($total_pages > 5 && ($current_page_number == 1 || $current_page_number == 2))
            echo '<li class="page-item"><a class="page-link" href="?view='.$current_view.'&current_page_number='.($current_page_number+1).'">'.($current_page_number+1).'</a></li>';

        // If there are more than 5 pages and current page is the last or second to last..
        if($total_pages > 5 && $current_page_number == $total_pages)
            echo '<li class="page-item"><a class="page-link" href="?view='.$current_view.'&current_page_number='.($current_page_number-1).'">'.($current_page_number-1).'</a></li>';

        // Show next page number if there are more than 5 pages and current
        // page number isn't first, second, second to last, or last...
        if($total_pages > 5 && $current_page_number != 1 && $current_page_number != 2 && $total_pages != ($current_page_number+1) && $current_page_number != $total_pages)
            echo '<li class="page-item"><a class="page-link" href="?view='.$current_view.'&current_page_number='.($current_page_number+1).'">'.($current_page_number+1).'</a></li>';

        // Show "..." if there are more than 3 pages and we're not on the page before,
        // the last display a disabled elipses so that the user knows to click 'next'.
        if($current_page_number != $total_pages && $total_pages > 3 && $current_page_number != ($total_pages-1) && $total_pages != ($current_page_number+2))
            echo '<li class="page-item disabled"><a class="page-link" href="">...</a></li>';
        ?>

        <li class="page-item <?php echo get_active_state($current_page_number, $total_pages)?>">
            <a class="page-link" href="?view=<?php echo $current_view;?>&current_page_number=<?php echo $total_pages; ?>"><?php echo $total_pages;?></a>
        </li>
        <li class="page-item <?php if($current_page_number >= $total_pages){ echo 'disabled'; } ?>">
            <a class="page-link" href="<?php if($current_page_number >= $total_pages){ echo '#'; } else { echo '?view='.$current_view.'&current_page_number='.($current_page_number + 1); } ?>">Next</a>
        </li>
    </ul>
</nav>

<?php
    // End pagination.
    endif;
}

/**
 * The Alert View Options
 */
function the_alert_options($current_view_data){

    // Setup filters array to make it easier to return
    // filter data.
    $reformatted_filters = [
        'type' => '',
        'source' => '',
        'integration_uri' => ''
    ];
    
    // Setup data variables.
    if(empty($current_view_data)){
        $view_name = 'New View';
    }else{
        $view_name = $current_view_data['name'];
        $filters = $current_view_data['filters'];
        foreach($filters as $filter){
            if($filter['name'] == 'type')
                $reformatted_filters['type'] = $filter['value'];
            if($filter['name'] == 'source')
                $reformatted_filters['source'] = $filter['value'];
            if($filter['name'] == 'integration_uri')
                $reformatted_filters['integration_uri'] = $filter['value'];
        }

    }
?>

<div class="modal fade" id="alertOptions" aria-labelledby="filterModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title h4" id="filterModalLabel">"<span id="viewName"><?php echo $view_name;?></span>" View Options</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="actions/save_alert_options.php" method="post">
                <div class="modal-body">

                    <?php
                    // Get View Details
                    $alert_options = unserialize(DataAccess::get_meta_value('alert_options'));

                    // Show active integrations.
                    $active_integrations = unserialize(DataAccess::get_meta_value('active_integrations'));

                    // List active integrations.
                    if(!empty($active_integrations)):
                    ?>

                    <div class="mb-3">
                        <label for="integrationSelect" class="form-label fw-semibold">Integration</label>
                        <select id="integrationSelect" class="form-select" name="integration_uri">
                            <option value="" >Any</option>

                            <?php
                            // Display an option for each active integration
                            foreach($active_integrations as $integration){
                                echo '<option value="'.$integration.'">'.ucwords(str_replace('_', ' ', $integration)).'</option>';
                            }
                            ?>

                        </select>
                    </div>

                    <?php
                    // End active integrations
                    endif;
                    ?>
                    
                    <div class="mb-3">
                        <label for="typeSelect" class="form-label fw-semibold">Alert Type</label>
                        <select id="typeSelect" class="form-select" name="type">
                        <option value="">Any</option>

                        <?php 
                        // Set types as array so we can simplify the logic
                        // when building the option html.
                        $type_options = array(
                            'error', 'warning', 'notice'
                        );
                        
                        // Build options.
                        foreach ($type_options as $option)
                            echo '<option value="'.$option.'">'.ucwords($option).'</option>';
                        ?>
                
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sourceSelect" class="form-label fw-semibold">Alert Source</label>
                        <select id="sourceSelect" class="form-select" name="source">
                        <option value="">Any</option>
                        
                        <?php 

                        // Set sources as array so we can simplify the logic
                        // when building the option html.
                        $source_options = array(
                            'page', 'system'
                        );
                        
                        // Build options.
                        foreach ($source_options as $option)
                            echo '<option value="'.$option.'">'.ucwords($option).'</option>';
                        ?>

                        </select>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="viewNameInput" class="form-label fw-semibold">View Name</label>
                        <input type="text" id="viewNameInput" class="form-control" aria-describedby="metaFilter1Help" value="<?php echo $view_name;?>" name="view_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <?php
                    // Users shouldn't delete a view if it's the only view
                    // that exists.
                    if(count($alert_options['views']) > 1)
                        echo '<a type="submit" href="actions/delete_alert_view.php" class="btn btn-outline-danger">Delete View</a>';
                    ?>

                    <button type="submit" class="btn btn-primary" id="saveButton">Save Options</button>
                </div>
                <input type="hidden" name="existing_view" value="" id="existingViewInput">
            </form>
        </div>
    </div>
</div>
<script>
// Define fields and buttons.
const integrationSelect = document.getElementById('integrationSelect');
const typeSelect = document.getElementById('typeSelect');
const sourceSelect = document.getElementById('sourceSelect');
const viewNameInput = document.getElementById('viewNameInput');
const existingViewInput = document.getElementById('existingViewInput');
const addViewButton = document.getElementById('addViewButton');
const editViewOptionsButton = document.getElementById('editViewOptionsButton');
const saveButton = document.getElementById('saveButton');
const viewName = document.getElementById('viewName');

// Change view name text as you type.
const changeViewName = function(e) {
    viewName.innerHTML = e.target.value;
}
viewNameInput.addEventListener('input', changeViewName);
viewNameInput.addEventListener('propertychange', changeViewName);

// Set "new_view" save action.
const setNewViewSaveAction = function(e) {
    viewName.innerHTML = 'Unnamed';
    viewNameInput.value = 'Unnamed';
    integrationSelect.value = '';
    typeSelect.value = '';
    sourceSelect.value = '';
    saveButton.innerHTML = 'Create New View';
}
addViewButton.addEventListener('click', setNewViewSaveAction);

// Set "update_view" action.
const setUpdateViewSaveAction = function(e) {
    viewName.innerHTML = '<?php echo $view_name;?>';
    viewNameInput.value = '<?php echo $view_name;?>';
    integrationSelect.value = '<?php echo $reformatted_filters['integration_uri'];?>'
    typeSelect.value = '<?php echo $reformatted_filters['type'];?>'
    sourceSelect.value = '<?php echo $reformatted_filters['source'];?>'
    existingViewInput.value = '<?php echo $view_name;?>';

}
editViewOptionsButton.addEventListener('click', setUpdateViewSaveAction);

</script>

<?php
}
?>