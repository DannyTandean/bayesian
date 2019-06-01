<?php
  defined('BASEPATH') OR exit('No direct script access allowed');

  function modalButtonTarget($idTarget,$nameButton,$color="primary",$icon="fa-plus",$size="")
  {
    return '
          <button class="btn btn-'.$color.' btn-'.$size.'" data-toggle="modal" data-target="#'.$idTarget.'"><span class="fa '.$icon.'"></span>'." ".$nameButton.'</button><br><br>
        ';
  }

  function modalAnchorTarget($idTarget,$url,$nameButton,$color="default",$icon="fa-plus",$size="")
  {
    return '
            <a href="'.$url.'" data-toggle="modal" data-target="#'.$idTarget.'" class="btn btn-'.$color.'"><span class="fa '.$icon.'"></span>'." ".$nameButton.'</a>
          ';
  }

  /**
  * @param $idTarget = id modal
  * @param $size = "lg or sm "
  * @param $color = "primary or info or warning or danger or success"
  * @param $title = "title header modal";
  */
  function modalSaveOpen($idTarget=false,$size = "",$color="black",$title="Empty Title")
  {
    $idTarget = $idTarget == false ? "modalForm" : $idTarget;
    return '
              <div class="modal fade " id="'.$idTarget.'" style="z-index:1059;">
              <div class="modal-dialog modal-'.$size.'">
                <div class="modal-content">
                  <div class="modal-header modal-header-'.$color.'">
                    <h5 class="modal-title title-close">'.$title.'</h5>
                    <button type="button" class="close title-close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-times-circle"></i></button>
                  </div>
                  <div class="modal-body">
          ';
  }

  function modalSaveClose($nameButton="Save",$id = "modalButtonSave",$btnColor = "primary",$onclick=null)
  {
    $funcOnclick = $onclick != null ? 'onclick="'.$onclick.'"' : '';
    return '
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-'.$btnColor.'" id="'.$id.'" '.$funcOnclick.'>'.$nameButton.'</button>
                    <button type="button" class="btn btn-default" id="modalButtonClose" data-dismiss="modal">Close</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          ';
  }

  function modalSaveClosePrint($nameButton="Save",$id = "modalButtonSave",$btnColor = "primary",$onclick=null)
  {
    $funcOnclick = $onclick != null ? 'onclick="'.$onclick.'"' : '';
    return '
                  </div>
                  <div class="modal-footer">
                    <button type="button" id="btnGenerate" class="btn btn-warning">Generate</button>
                    <button type="button" class="btn btn-'.$btnColor.'" id="'.$id.'" '.$funcOnclick.'>'.$nameButton.'</button>
                    <button type="button" class="btn btn-default" id="modalButtonClose" data-dismiss="modal">Close</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          ';
  }

  function modalCloseButtonCustom($btnHtml="")
  {
     return '
                  </div>
                  <div class="modal-footer">
                    '.$btnHtml.'
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          ';
  }

  /**
  * @param $idTarget = id modal
  * @param $size = "lg or sm "
  * @param $color = "primary or info or warning or danger or success"
  * @param $title = "title header modal";
  */
  function modalDetailOpen($idTarget=false,$size = "",$color="black",$title="Empty Title")
  {
    $idTarget = $idTarget == false ? "modalDetail" : $idTarget;
    return '
              <div class="modal fade " id="'.$idTarget.'" style="z-index:1059;">
              <div class="modal-dialog modal-'.$size.'">
                <div class="modal-content">
                  <div class="modal-header modal-header-'.$color.'">
                    <h5 class="modal-title title-close">'.$title.'</h5>
                    <button type="button" class="close title-close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-times-circle"></i></button>
                  </div>
                  <div class="modal-body">
          ';
  }

  function modalDetailClose()
  {
    return '
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" id="modalButtonCloseDetail" data-dismiss="modal">Close</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          ';
  }

  function modalDeleteShow($id="modalDelete",$title = "Delete",$idbtn="modalButtonDelete")
  {
    return '
               <div class="modal fade" id="'.$id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                  <div class="modal-content">
                    <div class="modal-header modal-header-danger">
                      <h5 id="modalTitleDelete" class="title-close">'.$title.'</h5>
                      <button type="button" class="close title-close" id="modalButtonCloseDelete" data-dismiss="modal" aria-label="Close"><i class="fa fa-times-circle"></i></button>
                    </div>
                    <div class="modal-body">
                      <div class="contentDelete"><p><b>Apakah anda yakin ingin menghapus data ini.?</b></p></div>
                      <div class="inputData"></div>
                      <div class="inputMessageDelete"></div>
                   </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-warning" id="'.$idbtn.'">OK Delete</button>
                      <button type="button" class="btn btn-default" id="modalButtonCloseDelete" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
          ';
  }

  /**
  * @param $idTarget = id modal
  * @param $size = "lg or sm "
  * @param $title = "title header modal";
  */
  function modalDeleteOpen($idTarget=false,$size = "",$title="Delete Title")
  {
    $idTarget = $idTarget == false ? "modalDeleteForm" : $idTarget;
    return '
              <div class="modal fade" id="'.$idTarget.'">
              <div class="modal-dialog modal-'.$size.'">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <i class="fa fa-times-circle"></i></button>
                    <h5 class="modal-title">'.$title.'</h5>
                  </div>
                  <div class="modal-body">
          ';
  }

  function modalDeleteClose($nameButton="Delete",$id = "modalButtonDeleteForm",$btnColor = "warning")
  {
    return '
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-'.$btnColor.'" id="'.$id.'">'.$nameButton.'</button>
                    <button type="button" class="btn btn-default" id="modalButtonCloseDeleteForm" data-dismiss="modal">Close</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          ';
  }

  function modalInfo($idModal=false,$message=false)
  {
    $idModal = $idModal != false ? $idModal : "modalInfo";
    $message = $message != false ? $message : "Prosess berhasil..";
    return '
              <div class="modal fade" id="'.$idModal.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-body">
                      <center>
                      <div id="contentSpinner"><span style="color:blue;"><i style="font-size: 40px;" class="fa fa-spinner fa-spin"></i><b style="font-size: 20px;"> Menunggu prosess....</b></span></div>
                      <div id="contentMessageInfo" style="color:green;"><b style="font-size:20px;" id="inputMessageInfo">'.$message.'</b><i style="font-size: 40px;" class="fa fa-check"></i></div>
                      </center>
                   </div>
                  </div>
                </div>
              </div>
          ';
  }

  function alertSuccess($message)
  {
      return '<div class="alert alert-success text-center orange"><b>'.$message.'</b></div><br>';
  }

  function alertInfo($message)
  {
      return '<div class="alert alert-info text-center orange"><b>'.$message.'</b></div><br>';
  }

  function alertWarning($message)
  {
      return '<div class="alert alert-warning text-center orange"><b>'.$message.'</b></div><br>';
  }

  function alertDanger($message)
  {
      return '<div class="alert alert-danger text-center orange"><b>'.$message.'</b></div><br>';
  }

  function spanRed($message)
  {
      return '<span style="color:red;">'.$message.'</span>';
  }

  function spanGreen($message)
  {
      return '<span style="color:green;">'.$message.'</span>';
  }
