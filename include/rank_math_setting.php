<?php $message='';
if(isset($_POST['rankmath_graph_val'])){
    $obj=$_POST;
    $data_name = sanitize_text_field($obj['data_name']);
    $data_uv = sanitize_text_field($obj['data_uv']);
    $data_pv = sanitize_text_field($obj['data_pv']);
    $data_value = sanitize_text_field($obj['data_value']);
    if(!$obj['data_name'] || !$obj['data_uv'] || !$obj['data_pv'] || !$obj['data_pv']  ){
        $message= esc_html__('Please fill all data','rankmath');
    }else{
        global $table_prefix, $wpdb;
        $table_name = $table_prefix . 'rank_math_graph_data';
        $wpdb->insert($table_name, array('name' => $data_name, 'data_uv' => $data_uv,'data_pv' => $data_pv, 'value' => $data_value)); 
        $message= esc_html__('Graph Data added Successfully','rankmath');
    }

}
if(isset($_GET['d_id'])){
    global $table_prefix, $wpdb;
    $table_name = $table_prefix . 'rank_math_graph_data';
    $delete_id = sanitize_text_field($_GET['d_id']);
    $wpdb->delete( $table_name, array( 'id' => $delete_id ) );
    $message= esc_html__('Graph Data Deleted Successfully','rankmath');
}
?>
<div class="rank_math_graph_entry_wrap">
    
    
    <?php $admin_plugin_url = get_admin_page_url('rank-math-graph-dashboard');
    function get_admin_page_url(string $menu_slug, $query = null, array $esc_options = []) : string
    {
        $url = menu_page_url($menu_slug, false);
    
        if($query) {
            $url .= '&' . (is_array($query) ? http_build_query($query) : (string) $query);
        }
    
        return esc_url($url, ...$esc_options);
    }?>
    <div class="form_graph_wrap">
        <div class="rank_math_graph_data_form">
                <h2><?php echo esc_html__('Rank Math Graph Data Form','rankmath');?></h2>
                <form method = "post" action ="<?php echo $admin_plugin_url; ?>">
                    <input type="text" name="data_name" value="" placeholder="Name"><br>
                    <input type="text" name="data_uv" value="" placeholder="UV"><br>
                    <input type="text" name="data_pv" value="" placeholder="PV"><br>
                    <input type="text" name="data_value" value="" placeholder="Value"><br>
                    <input type="submit" name="rankmath_graph_val" value="Save Data">
                </form>
            <div class="rank_math_message"><?php echo esc_attr($message);?></div>
        </div>
        <?php $rest_api_url=site_url()."/wp-json/wl/v1/rankmathgraph/";?>
        <div id="rank_math_graph_root" data-url="<?php echo $rest_api_url;?>"></div>
    </div>
    <div class="rank_math_graph_list">
        <?php 
        global $table_prefix, $wpdb;
        $table_name = $table_prefix . 'rank_math_graph_data';
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY `id` DESC LIMIT 0, 30"));
        ?>
        <h2><?php echo esc_html__('Rank Math Graph Data List','rankmath');?></h2>
        <table style="with:500px;border: 1px solid;">
            <tr>
                <th><?php echo esc_html__('SN','rankmath');?></th>
                <th><?php echo esc_html__('Name','rankmath');?></th>
                <th><?php echo esc_html__('UV','rankmath');?></th>
                <th><?php echo esc_html__('PV','rankmath');?></th>
                <th><?php echo esc_html__('Value','rankmath');?></th>
                <th><?php echo esc_html__('Action','rankmath');?></th>
            </tr>
            <?php $i=1;
            foreach($results as $row)
            {   echo "<tr>";
                echo "<td>".$i++."</td><td>".esc_attr($row->name)."</td><td>".esc_attr($row->data_uv)."</td><td>".esc_attr($row->data_pv)."</td><td>".esc_attr($row->value)."</td>";
                $url=$admin_plugin_url.'&d_id='.$row->id;
                echo "<td><a onClick=\"javascript: return confirm('Please confirm deletion');\" href='". $url."'>Delete</a></td>";
                echo "</tr>";
            }?>
        </table>
    </div>
</div>
