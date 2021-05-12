<?php


/*
Generate a generic bootstrap pop-up window.

@param $title the text in the title bar
@param $text to be shown
@param $btnText text on the single button. 
*/
function generateGenericPop($id, $title, $text, $btnText){

$html = '


<!-- Modal for quick approve -->
<div class="modal" style="top:100px" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">'.$title.'</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
        '.$text.'
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">'.$btnText.'</button>
       
      </div>
    </div>
  </div>
</div>
';



return $html;
}




/*
Generate a generic bootstrap pop-up with a conf option.

@param $title the text in the title bar
@param $text to be shown
@param $btnText text on the single button. 
*/
function generateGenericConfirm($id, $title, $text, $btnText){

$html = '


<!-- e -->
<div class="modal" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">'.$title.'</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            '.$text.'
        
        
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">'.get_string('cancel','block_cmanager').'</button>
        <button type="button" class="btn btn-primary" id="ok'.$id.'">'.$btnText.'</button>
      </div>
    </div>
  </div>
</div>


';
return $html;
}