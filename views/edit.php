<div class="row">
        <div class="col-lg-6">
            <?php
                $primary_key = isset($primary_key[$schema]) ? $primary_key[$schema] : 'id';
            ?>
            <form id="smart-crud-edit-form" action="" method="post" enctype="multipart/form-data" data-item-id="<?php echo $item[$primary_key];?>">
            <?php
                foreach($attributes as $column_name => $column_details) {
                    if(!in_array($column_name, $ignore)) {
                        $column_label = (!isset($labels[$column_name])) ? ucfirst( str_replace( "_", " ", $column_name ) ) : $labels[$column_name];
                        $mendatory_sign = ( !in_array($column_name, $optional_fields ) ) ? '<span style="color:red">*</span>' : "" ;
                        echo '<div class="form-group">';
                        $label = '<label class="control-label">' . $column_label . $mendatory_sign .'</label>';
                        if ( in_array($column_name, $image_fields) ) {
	                        echo $label;
	                        echo "<input type='file' name='".$column_name."' class='form-control'>";
                        } else if ( array_key_exists($column_name, $relational_fields[$schema]) ) {
	                        $multiple = ($relational_fields[$schema][$column_name][3] == true) ? 'multiple' : '';
	                        $select_html = "<select name='".$column_name."' class='form-control' ".$multiple.">";
	                        foreach($relational_fields_data[$schema][$column_name] as $rk => $rd) {
		                        $selected = (in_array($rk, explode(",", $item[$column_name]))) ? "selected" : "";
		                        $select_html .= "<option value='".$rk."' ".$selected.">".$rd."</option>";
	                        }
	                        $select_html .= "</select>";
	                        echo $label;
	                        echo $select_html;
                        } else if ( isset($item[$column_name]) && array_key_exists($column_name, $checkbox_fields ) ) {
	                        $checked = ( $checkbox_fields[$column_name]['value'] == $item[$column_name] ) ? true : null;
	                        echo $label;
	                        echo '<input type="checkbox" name="'.$column_name.'" value="'.$checkbox_fields[$column_name]['value'].'" '.$checked.'>';
                        } else if ( isset($item[$column_name]) && array_key_exists($column_name, $radio_fields ) ) {
	                        echo $label;
	                        echo "</br>";
	                        foreach($radio_fields[$column_name] as $value => $label) {
	                        	echo '<input type="radio" name="'.$column_name.'" value="'.$value.'">'.'  '. $label;
		                        echo "</br>";
	                        }
                        }  else {
	                        echo $label;
	                        $value = isset($_POST[$column_name]) ? $_POST[$column_name] : $item[$column_name];
	                        echo '<input type="text" name="'.$column_name.'" value="'.$value.'" class="form-control">';
                        }
                        /*if ( $errors->has($column_name) ) {
	                        echo' <p class="validation-msg">'.$errors->first($column_name).'</p>';
                        }*/
                        echo '</div>';
                    }
                }
            ?>
				<input type="hidden" name="<?php echo $primary_key;?>" value="<?php echo $item[$primary_key];?>">
	            <button id="btn-edit-save" type="button" class="btn btn-primary">Save</button>
	            <a id="btn-edit-cancel" href="javascript:void(0)" class="btn btn-danger">Cancel</a>
	        </form>
        </div>
        <div class="col-lg-6">
               <!--image code-->
        </div>
    <!-- /.row (nested) -->
</div>