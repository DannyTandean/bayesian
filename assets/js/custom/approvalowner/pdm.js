$(document).ready(function() {

  btnTambah = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm' id='btnTambah'><i class='fa fa-plus'></i> Tambah</button>";

    btnRefresh = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-success btn-sm' id='btnRefresh'><i class='fa fa-refresh'></i> Refresh</button>";

  $("#tblPromosi").DataTable({
    serverSide:true,
    responsive:true,
    processing:true,
    oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   "+btnRefresh,
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
    //load data
    ajax: {
      url: base_url+'approvalowner/pdm/ajax_list',
      type: 'POST',
    },

    order:[[3,'ASC']],
    columns:[
      {
        data:'no',
        searchable:false,
        orderable:false,
      },
      {
        data:'button_action',
        searchable:false,
        orderable:false,
      },
      { data:'button_tools',
        searchable:false,
        orderable:false,
       },
      { data:'judul' },
      { data:'nama' },
      { data:'id_cabang2.cabang' },
      { data:'id_departemen2.departemen' },
      { data:'id_jabatan2.jabatan' },
      { data:'id_grup2.grup' },
      { data:'id_golongan2.golongan' },
      { data:'keterangans' },
      { data:'status' },
      { data :'status_info' },
      { data:'create_at' },
    ],
    dom : "<'row' <'col-md-5'l> <'col-md-3'B> <'col-md-4'f>>" + "<'row' <'col-md-12't>r>" + "<'row' <'col-md-6'i> <'col-md-6'p>>",
    buttons: [
        'colvis'
    ],
  });
  $("#tanggal").dateDropper( {
          dropWidth: 200,
          init_animation: "bounce",
          dropPrimaryColor: "#1abc9c",
          dropBorder: "1px solid #1abc9c",
          maxYear: "2050",
          format: "Y-m-d"
      });
});

function reloadTable() {
  $("#tblPromosi").DataTable().ajax.reload(null,false);
}

var save_method;
var idData;

$(document).on('click', '#btnRefresh', function(e) {
  e.preventDefault();
  reloadTable();
  $("#btnRefresh").children().addClass("fa-spin");
  setTimeout(function(){
    $("#btnRefresh").children().removeClass("fa-spin");
  }, 1000);
});

function btnDetail(id)
{
  $("#Details").modal("show");
  $(".modal-title").text("Details");

  $.post(base_url+"/approvalowner/pdm/getId2Model/"+id,function(json) {
    if(json.status == true){
          $("#noid2").html(json.data.kode_karyawan);
          $("#detail_img_photo").attr("src",json.data.foto);
          $('#detailPopUpPhotoOut').attr('href',json.data.foto);
          $('#detailPopUpPhotoIn').attr('src',json.data.foto);
          $("#nama2").html(json.data.nama);
          $("#cabang2").html(json.data.cabang);
          $("#departemen2").html(json.data.departemen);
          $("#jabatan2").html(json.data.jabatan);
          $("#grup2").html(json.data.grup);
          $("#golongan2").html(json.data.golongan);
        
          $("#keterangan2").html(json.data.keterangans);
          $("#tglDetail").html(json.data.approve_at);
    } else {
      $("#inputMessage").html(json.message);


      $("#cabang2").html("");
      $("#departemen2").html("");
      $("#jabatan2").html("");
      $("#grup2").html("");
      $("#golongan2").html("");
      $("#keterangan2").html("");
      // setTimeout(function() {
      //  reloadTable();
      //  $("#Print").modal("hse");
      // },1000);
    }
  });
}
$("#btnDetailZoomImg").click(function() {
  $("#detailPopUpPhotoOut").click();
});

$("#btnDetailZoomFile").click(function() {
  $("#detailPopUpFileOut").click();
});

function btnApproval(id) {
  idData = id;
  $.post(base_url+"approvalowner/pdm/getId/"+idData,function(json) {
    if (json.status == true) {
      $("#status").val("Diterima");
      $("#statusInfo").val(json.data.status_info);
      $("#id_promosi").val(json.data.id_promosi);
      $("#id_karyawan3").val(json.data.id_karyawan);
     if(json.data.status == "Proses") {
        var pesan = "<hr>";
          pesan += "<div class='row'>";
          pesan += "<div class='col-md-4'>";
          pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
          pesan += "</div>";
          pesan += "<div class='col-md-8'>";
          pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Cabang : <i>"+json.data.cabang+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Grup : <i>"+json.data.grup+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";
          pesan += "</div>";
          pesan += "</div>";

        swal({
            title: "Apakah anda yakin.?",
            html: "<span style='color:green;'>Data yang sudah <b>Diterima</b> tidak bisa di edit/update lagi.</span>"+pesan,
            type: "question",
        width: 400,
          showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: "#009900",
            confirmButtonText: "Iya, Terima",
            closeOnConfirm: false,
          // background: '#e9e9e9',

        }).then((result) => {
          if (result.value) {


            $.ajax({
              url: base_url+"approvalowner/pdm/update/"+idData,
              type:'POST',
              data:$("#formData").serialize(),
              dataType:'JSON',
              success: function(json) {
            if (json.status == true) {

              swal({
                        title: json.message,
                        type: "success",
                        // html: true,
                        timer: 2000,
                        showConfirmButton: false
                    });
              reloadTable();
            } else {
              swal({
                        title: json.message,
                        type: "error",
                        // html: true,
                        timer: 1500,
                        showConfirmButton: false
                    });
              reloadTable();
            }
          }
        });
      }
    });
  }else if (json.data.status == "Diterima") {

    swal({
              title: "<h2 style='color:orange;'>Data yang sudah <u style='color:green;'>Diterima</u> tidak bisa Diterima lagi.!</h2>",
              type: "warning",
              timer: 3000,
              showConfirmButton: false

          });
  } else if (json.data.status == "Ditolak") {
    swal({
              title: "<h2 style='color:orange;'>Data yang sudah <u style='color:red;'>Ditolak</u> tidak bisa Diterima lagi.!</h2>",
              type: "warning",
              timer: 3000,
              showConfirmButton: false

          });
  }
    } else {
      reloadTable();
      swal({
                title: json.message,
                type: "error",
                // html: true,
                timer: 1000,
                showConfirmButton: false
            });
    }
  });

}


function btnReject(id) {
  idData = id;

  $.post(base_url+"approvalowner/pdm/getId/"+idData,function(json) {

    if (json.status == true) {
        $("#status").val("Ditolak");
        $("#statusInfo").val(json.data.status_info);
      $("#id_promosi").val(json.data.id_promosi);
      $("#id_karyawan3").val(json.data.id_karyawan);
       if(json.data.status == "Proses") {
        var pesan = "<hr>";
          pesan += "<div class='row'>";
          pesan += "<div class='col-md-4'>";
          pesan += '<img class="img-fluid img-circle" src="'+json.data.foto+'" alt="user-img" style="height: 100px; width: 100px;">';
          pesan += "</div>";
          pesan += "<div class='col-md-8'>";
          pesan += "<li class='pull-left'><small>Nama : <i>"+json.data.nama+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Status : <i>"+json.data.status+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Departemen : <i>"+json.data.departemen+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Jabatan : <i>"+json.data.jabatan+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Cabang : <i>"+json.data.cabang+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Grup : <i>"+json.data.grup+"</i></small></li><br>";
          pesan += "<li class='pull-left'><small>Keterangan : <i>"+json.data.keterangans+"</i></small></li><br>";
          pesan += "</div>";
          pesan += "</div>";


        swal({
            title: "Apakah anda yakin.?",
            html: "<span style='color:red;'>Data yang <b>Ditolak</b> tidak bisa di edit/update lagi.</span>"+pesan,
            type: "question",
        width: 400,
          showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Iya, Tolak",
            closeOnConfirm: false,
          // background: '#e9e9e9',

        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: base_url+"approvalowner/pdm/update/"+idData,
              type:'POST',
              data:$("#formData").serialize(),
              dataType:'JSON',
              success: function(json) {
            if (json.status == true) {

              swal({
                        title: json.message,
                        type: "success",
                        // html: true,
                        timer: 2000,
                        showConfirmButton: false
                    });
              reloadTable();
            } else {
              swal({
                        title: json.message,
                        type: "error",
                        // html: true,
                        timer: 1500,
                        showConfirmButton: false
                    });
              reloadTable();
            }
          }
        });
      }
    });
  }else if (json.data.status == "Diterima") {
      swal({
                title: "<h2 style='color:orange;'>Status yang sudah <u style='color:green;'>Diterima</u> tidak bisa Ditolak lagi.!</h2>",
                type: "warning",
                timer: 3000,
                showConfirmButton: false

            });
    } else if (json.data.status == "Ditolak") {
      swal({
                title: "<h2 style='color:orange;'>Status yang sudah <u style='color:red;'>Ditolak</u> tidak bisa di Ditolak lagi.!</h2>",
                type: "warning",
                timer: 3000,
                showConfirmButton: false

            });
    }
    } else {
      reloadTable();
      swal({
                title: json.message,
                type: "error",
                // html: true,
                timer: 1000,
                showConfirmButton: false
            });
    }
  });
}
