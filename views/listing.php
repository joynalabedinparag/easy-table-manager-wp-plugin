
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-default" id="item-add-btn"><span class="glyphicon glyphicon-plus"> Add</button>
    </div>
</div>

<table class="table table-striped table-bordered table-hover">
	<thead>
	<tr class="">
	<?php $c = 1;
	    foreach($attributes as $column_name => $column_details):
		    $column_label = (!isset($labels[$column_name])) ? ucwords( str_replace( "_", " ", $column_name ) ) : $labels[$column_name];
	        if(!in_array($column_name, $ignore)): ?>
	            <th><?php echo ucwords( str_replace( "_", " ", $column_label ) ); ?></th>
				<?php
	                if( $c >= $max_index_column) {break;}
	                $c++;
	        endif;
	    endforeach;
	?>
	    <th>Action</th>
	</tr>
	</thead>

	<tbody>
    <?php
    $primary_key = isset($primary_key[$schema]) ? $primary_key[$schema] : 'id';
    $i = 0;
	if( count($items) >= 1 ):
		foreach( $items as $item ) : ?>
		<tr class="odd gradeX tr_<?php  echo $item->$primary_key;?>">
			<?php $c = 1;
			foreach($attributes as $column_name => $column_details) :
				if(!in_array($column_name, $ignore)) :
					if (isset($relational_fields[$schema]) && array_key_exists($column_name, $relational_fields[$schema])) : ?>
						<td>
							<?php
							$imploded_data = '';
							foreach (explode(",", $item->$column_name) as $pc) {
								if(isset($relational_fields_data[$schema][$column_name][$pc])) {
									$imploded_data .= $relational_fields_data[$schema][$column_name][$pc]. ", ";
								} else {
									$imploded_data .= $pc;
								}
							}
							echo rtrim($imploded_data, ", ");
							?>
						</td>
					<?php
					elseif (array_key_exists($column_name, $radio_fields)) : ?>
						<td>
							<?php $radio_fields[$column_name][$item->$column_name]; ?>
						</td>
					<?php
					elseif (in_array($column_name, $image_fields)): ?>
						<td>
							<?php
							if(file_exists( public_path() .'/images/catalog/products/'.$item->$column_name)) : ?>
								<a target="_blank" href="{{ asset('images/catalog/products/'.$item->$column_name) }}">View Image</a>
							<?php
							else:
								echo "No image yet";
							endif;?>
						</td>
					<?php
					else: ?>
						<td><?php echo $item->$column_name; ?></td>
					<?php
					endif;

					if( $c >= $max_index_column) {break;}
					$c++;
				endif;
			endforeach;
			?>
			<td>
				<a style="display:none" href="javascript:void(0)" ><button type="button" class="btn btn-warning btn-circle" data-toggle="tooltip" title="Preview"><span class="glyphicon glyphicon-eye-open"></span> </button></a>
				<a href="javascript:void(0)" data-item-id="<?php echo $item->$primary_key;?>" class="item-edit-btn">
                    <button  type="button" class="btn btn-success btn-circle" data-toggle="tooltip" title="Edit"><span class="glyphicon glyphicon-edit"></span> </button>
                </a>
				<a href="javascript:void(0)" data-item-id="<?php echo $item->$primary_key;?>" class="item-delete-btn">
                    <button type="button" class="btn btn-danger btn-circle" data-toggle="tooltip" title="Delete"><span class="glyphicon glyphicon-trash"></span> </button>
                </a>
			</td>

		</tr>
        <?php
        endforeach;
	else: ?>
		<tr>
			<td colspan="50"><div class="alert alert-danger text-center">No Data Available</div></td>
		</tr>
	</tbody>
	<?php
	endif; ?>
</table>