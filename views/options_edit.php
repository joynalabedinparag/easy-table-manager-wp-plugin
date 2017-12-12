<div class="row">
    <div class="row">
            <div id="realtional_field_container" class="col-md-12">
                <?php
                foreach($relational_fields_options as $rfo_schema => $rfo) :
                    foreach($rfo as $rfo_key => $rfo_details) :
	                    $rfo_schema_attr         = $rfo_key;
	                    $rfo_related_schema      = $rfo_details[0];
	                    $rfo_related_schema_attr = $rfo_details[1];
	                    $rfo_related_schema_attr_label = $rfo_details[2];
                        ?>
                        <div class="relational_field col-md-8">
                            <div class="row">
                                <a href="javascript:void(0)" class="remove-relational_field_block"> <span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="schema">Table</label>
                                        <select class="schema form-control"  aria-describedby="emailHelp">
                                            <option value="">Select Table</option>
                                            <?php foreach($schemas as $schema):
                                                $selected = !empty($rfo_schema) && $rfo_schema == $schema ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $schema;?>" <?php echo $selected;?> ><?php echo $schema;?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <small id="emailHelp" class="form-text text-muted">Table inside which the column you want to set relation for exist.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="schema_attr">Attribute</label>
                                        <select class="schema_attr form-control" aria-describedby="emailHelp">
                                            <?php echo $this->etmo_generateSchemaAttributesOptions($rfo_schema, $rfo_schema_attr); ?>
                                        </select>
                                        <small id="emailHelp" class="form-text text-muted">Column for which you want to set the relation</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Relation with Table</label>
                                        <select class="related_schema form-control" aria-describedby="emailHelp">
                                            <option value="">Select Table</option>
                                            <?php foreach($schemas as $schema):
                                                $selected = !empty($rfo_related_schema) && $rfo_related_schema == $schema ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $schema;?>" <?php echo $selected; ?> ><?php echo $schema;?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <small id="emailHelp" class="form-text text-muted">Table inside which the column you want to set relation with exist.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="related_schema_attr">Related Column</label>
                                        <select class="related_schema_attr form-control" aria-describedby="emailHelp">
                                            <?php echo $this->etmo_generateSchemaAttributesOptions($rfo_related_schema, $rfo_related_schema_attr); ?>
                                        </select>
                                        <small id="emailHelp" class="form-text text-muted">Column with which you want to set the relation</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="related_schema_attr">Related Display Column</label>
                                        <select class="related_schema_attr_label form-control" aria-describedby="emailHelp">
                                            <?php echo $this->etmo_generateSchemaAttributesOptions($rfo_related_schema, $rfo_related_schema_attr_label); ?>
                                        </select>
                                        <small id="emailHelp" class="form-text text-muted">This Column data will be shown in the table</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endforeach;
                endforeach;

                if (empty($relational_fields_options)) :
                ?>

                <div class="relational_field col-md-8">
                    <div class="row">
                        <a href="javascript:void(0)" class="remove-relational_field_block"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="schema">Table</label>
                                <select class="schema form-control"  aria-describedby="emailHelp">
                                    <option value="">Select Table</option>
                                    <?php foreach($schemas as $schema): ?>
                                        <option value="<?php echo $schema;?>"><?php echo $schema;?></option>
                                    <?php endforeach;?>
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Table inside which the column you want to set relation for exist.</small>
                            </div>

                            <div class="form-group">
                                <label for="schema_attr">Attribute</label>
                                <select class="schema_attr form-control" aria-describedby="emailHelp">

                                </select>
                                <small id="emailHelp" class="form-text text-muted">Column for which you want to set the relation.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Relation with Table</label>
                                <select class="related_schema form-control" aria-describedby="emailHelp">
                                    <option value="">Select Table</option>
                                    <?php foreach($schemas as $schema): ?>
                                        <option value="<?php echo $schema;?>"><?php echo $schema;?></option>
                                    <?php endforeach;?>
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Table inside which the column you want to set relation with exist.</small>
                            </div>

                            <div class="form-group">
                                <label for="related_schema_attr">Related Column</label>
                                <select class="related_schema_attr form-control" aria-describedby="emailHelp">

                                </select>
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>

                            <div class="form-group">
                                <label for="related_schema_attr">Related Display Column</label>
                                <select class="related_schema_attr_label form-control" aria-describedby="emailHelp">

                                </select>
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div> <!--end relational field container-->
    </div>

    <div class="row">
        <div class="col-md-8">
            <button type="button" id="btn_add_new_relational_block" class="btn btn-default"> Define Another Relation </button>
        </div>
    </div>

    <div class="row">
        <button type="button" id="btn_add_relational_data" class="btn btn-primary">Submit</button>
    </div>
</div>