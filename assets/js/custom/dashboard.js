

'use strict';
$(document).ready(function() {

    $.post(base_url+"/dashboard/chartKehadiranAjax", function(resp) {

        $("#tanggalKehadiran").html(resp.tanggal_indo);

        var chart = c3.generate({
            bindto: '#chart',
            color: {
                pattern: ['#239a55', '#e01c04', '#0073b7','#f39c12', '#00c0ef', '#001f3f','#605ca8']
            },
            data: {
                columns : resp.dataColumns,
                type : 'donut',
            },
            donut: {
                title: "Kehadiran Harian"
            },
            legend: {
                position: 'right'
            }
        });
        /*for chart kehadiran*/
        $("#totalKaryawanCount").html(resp.dataCount.total_karyawan);
        $("#hadirCount").html(resp.dataCount.hadir);
        $("#mangkirCount").html(resp.dataCount.mangkir);
        $("#sakitCount").html(resp.dataCount.sakit);
        $("#izinCount").html(resp.dataCount.izin);
        $("#cutiCount").html(resp.dataCount.cuti);
        // $("#offCount").html(resp.dataCount.off);
        $("#dinasCount").html(resp.dataCount.dinas);

        /*for data kehadiran per shift*/
        $("#regularCount").html(resp.shiftCount.regular);
        $("#shiftPagiCount").html(resp.shiftCount.pagi);
        $("#shiftSoreCount").html(resp.shiftCount.sore);
        $("#shiftMalamCount").html(resp.shiftCount.malam);
        $("#shiftPagi12Count").html(resp.shiftCount.pagi12);
        $("#shiftMalam12Count").html(resp.shiftCount.malam12);
        $("#shiftManualCount").html(resp.shiftCount.manual);
        $("#extraKerjaCount").html(resp.shiftCount.extra);

        /*for data ketepatan kehadiran*/
        $("#hadirTepatCount").html(resp.dataKetepatan.tepat);
        $("#hadirTerlambatCount").html(resp.dataKetepatan.terlambat);
        $("#totalKehadiaranCount").html(resp.dataKetepatan.total_kehadiran);

        /*for kontrak karyawan*/
        $("#sisa1Bulan").html(resp.dataKontrak.sisa1_bulan);
        $("#sisa2Bulan").html(resp.dataKontrak.sisa2_bulan);
        $("#sisa3Bulan").html(resp.dataKontrak.sisa3_bulan);
        $("#habisKontrakBulan").html(resp.dataKontrak.habis_kontrak);

        /*for ulang tahun*/
        if (resp.dataUlangTahun != "") {
            $("#dataUlangTahun").html(resp.dataUlangTahun);
            $("#headerUlangTahun").show(700);
        } else {
             $("#headerUlangTahun").hide(700);
             $("#dataUlangTahun").html("");
        }

        /*for kehadiran per departemen*/
        $("#absensiDepartemen").html(resp.dataDepartemen);
        // $(".dial").knob(); // for tooltip title
        dialKnob();

    });

});

$(document).ready(function() {
  $.post(base_url+"/dashboard/getjadwal", function(json) {
    if (json.status == true) {
      $.each(json.data,function(index,val) {
        $("#"+val.id_jadwal+"Count").parent().click(function() {
          if (valTglChart != "") {
            var table = $("#table"+val.id_jadwal).DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/"+val.id_jadwal+"/"+valTglChart).load();
          }
          $(".modal-title").html(val.nama_jadwal);
          $("#modal"+val.id_jadwal).modal("show");
        });
        dataTableGeneral("table"+val.id_jadwal,"dashboard/dataTableKehadiranShift/",val.nama_jadwal);
      });
      dataTableGeneral("tabletakterjadwal","dashboard/dataTableKehadiranShift/","takterjadwal");
    }
  });
});

var valTglChart = ""; // tampung data tglChartKehadiran
$("#tglChartKehadiran").change(function() {
    var val = $(this).val();
    if (val != "") {
        $.post(base_url+"/dashboard/chartKehadiranAjax/"+val, function(resp) {

            $("#tanggalKehadiran").html(resp.tanggal_indo);

            var chart = c3.generate({
                bindto: '#chart',
                color: {
                    pattern: ['#239a55', '#e01c04', '#0073b7','#f39c12', '#00c0ef', '#001f3f','#605ca8']
                },
                data: {
                    columns : resp.dataColumns,
                    type : 'donut',
                },
                donut: {
                    title: "Kehadiran Harian"
                },
                legend: {
                    position: 'right'
                }
            });
            /*for chart kehadiran*/
            $("#totalKaryawanCount").html(resp.dataCount.total_karyawan);
            $("#hadirCount").html(resp.dataCount.hadir);
            $("#mangkirCount").html(resp.dataCount.mangkir);
            $("#sakitCount").html(resp.dataCount.sakit);
            $("#izinCount").html(resp.dataCount.izin);
            $("#cutiCount").html(resp.dataCount.cuti);
            // $("#offCount").html(resp.dataCount.off);
            $("#dinasCount").html(resp.dataCount.dinas);

            /*for data kehadiran per shift*/
            $.post(base_url+"/dashboard/getjadwal", function(json) {
              if (json.status == true) {
                $.each(json.data,function(index, vals) {
                  $('#'+vals.id_jadwal+"Count").html(resp.shiftCount["shift"+vals.id_jadwal]);

                });
              }
            })
            $('#takterjadwalCount').html(resp.shiftCount.takterjadwal)
            $("#regularCount").html(resp.shiftCount.regular);
            $("#shiftPagiCount").html(resp.shiftCount.pagi);
            $("#shiftSoreCount").html(resp.shiftCount.sore);
            $("#shiftMalamCount").html(resp.shiftCount.malam);
            $("#shiftPagi12Count").html(resp.shiftCount.pagi12);
            $("#shiftMalam12Count").html(resp.shiftCount.malam12);
            $("#extraKerjaCount").html(resp.shiftCount.extra);

            /*for data ketepatan kehadiran*/
            $("#hadirTepatCount").html(resp.dataKetepatan.tepat);
            $("#hadirTerlambatCount").html(resp.dataKetepatan.terlambat);
            $("#totalKehadiranCount").html(resp.dataCount.hadir);

            /*for ulang tahun*/
            if (resp.dataUlangTahun != "") {
                $("#dataUlangTahun").html(resp.dataUlangTahun);
                $("#headerUlangTahun").show(700);
            } else {
                 $("#headerUlangTahun").hide(700);
                 $("#dataUlangTahun").html("");
            }

            /*for kehadiran per departemen*/
            $("#absensiDepartemen").html(resp.dataDepartemen);
            // $(".dial").knob(); // for tooltip title
            dialKnob();
        });

        valTglChart = val;
    }
});

function dialKnob() {
    $(".dial").knob({
            draw: function() {
                // "tron" case
                if (this.$.data('skin') == 'tron') {
                    this.cursorExt = 0.3;
                    var a = this.arc(this.cv) // Arc
                        ,
                        pa // Previous arc
                        , r = 1;
                    this.g.lineWidth = this.lineWidth;
                    if (this.o.displayPrevious) {
                        pa = this.arc(this.v);
                        this.g.beginPath();
                        this.g.strokeStyle = this.pColor;
                        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, pa.s, pa.e, pa.d);
                        this.g.stroke();
                    }
                    this.g.beginPath();
                    this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, a.s, a.e, a.d);
                    this.g.stroke();
                    this.g.lineWidth = 2;
                    this.g.beginPath();
                    this.g.strokeStyle = this.o.fgColor;
                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
                    this.g.stroke();
                    return false;
                }
            }
        });
}

$(document).ready(function() {
    /*for total karyawan datatable*/
    dataTableGeneral("tableTotalKaryawan","dashboard/dataTableKaryawan");
    /*hadir datatable*/
    dataTableGeneral("tableHadir","dashboard/dataTableKehadiranAbsensi");
    /*tidak hadir datatable*/
    dataTableGeneral("tableMangkir","dashboard/dataTableTidakHadir");
    /*sakit datatable*/
    dataTableGeneral("tableSakit","dashboard/dataTableKehadiranAbsensi/sakit");
    /*izin datatable*/
    dataTableGeneral("tableIzin","dashboard/dataTableKehadiranAbsensi/izin");
    /*cuti datatable*/
    dataTableGeneral("tableCuti","dashboard/dataTableKehadiranAbsensi/cuti");
    /*off datatable*/
    // dataTableGeneral("tableOff","dashboard/dataTableKehadiranAbsensiOff");
    /*dinas datatable*/
    dataTableGeneral("tableDinas","dashboard/dataTableKehadiranAbsensi/dinas");
    /*tepat datatable*/
    dataTableGeneral("tableHadirTepat","dashboard/dataTableKehadiranAbsensi/tepat");
    /*terlambat datatable*/
    dataTableTerlambat("tableHadirTerlambat","dashboard/dataTableKehadiranAbsensi/terlambat");

    // for shift kehadiran
    /*shift regular datatable*/
    // dataTableGeneral("tableShiftRegular","dashboard/dataTableKehadiranShift");
    // /*shift pagi datatable*/
    // dataTableGeneral("tableShiftPagi","dashboard/dataTableKehadiranShift/pagi");
    // /*shift sore datatable*/
    // dataTableGeneral("tableShiftSore","dashboard/dataTableKehadiranShift/sore");
    // /*shift malam datatable*/
    // dataTableGeneral("tableShiftMalam","dashboard/dataTableKehadiranShift/malam");
    // /*shift pagi12 datatable*/
    // dataTableGeneral("tableShiftPagi12","dashboard/dataTableKehadiranShift/pagi12");
    // /*shift malam12 datatable*/
    // dataTableGeneral("tableShiftMalam12","dashboard/dataTableKehadiranShift/malam12");
    // /*shift manual datatable*/
    // dataTableGeneral("tableShiftManual","dashboard/dataTableKehadiranShift/manual");
    // /*shift extra datatable*/
    // dataTableGeneral("tableShiftExtra","dashboard/dataTableKehadiranAbsensiExtra");

    // for kontrak karyawan
    /*sisa 1 bulan datatable*/
    // dataTableKontrak("tableKontrakSisa_1","dashboard/dataTableKontrak");
    // /*sisa 2 bulan datatable*/
    // dataTableKontrak("tableKontrakSisa_2","dashboard/dataTableKontrak/sisa2bulan");
    // /*sisa 3 bulan datatable*/
    // dataTableKontrak("tableKontrakSisa_3","dashboard/dataTableKontrak/sisa3bulan");
    // /*habis kontrak datatable*/
    // dataTableKontrak("tableKontrakHabis","dashboard/dataTableKontrak/habiskontrak");

    dataTableKontrakManual("tableKontrakSisa_1","dashboard/dataTableKontrak");
    /*sisa 2 bulan datatable*/
    dataTableKontrakManual("tableKontrakSisa_2","dashboard/dataTableKontrak/sisa2bulan");
    /*sisa 3 bulan datatable*/
    dataTableKontrakManual("tableKontrakSisa_3","dashboard/dataTableKontrak/sisa3bulan");
    /*habis kontrak datatable*/
    dataTableKontrakManual("tableKontrakHabis","dashboard/dataTableKontrak/habiskontrak");

});

/* for data table and modal*/
function dataTableGeneral(idTable,urlTable) {
    $("#"+idTable).DataTable({
        serverSide:true,
        responsive:true,
        processing:true,
        oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   ",
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
        //load data
        ajax: {
            url: base_url+urlTable,
            type: 'POST',
        },

        order:[[2,'ASC']],
        columns:[
            {
                data:'no',
                searchable:false,
                orderable:false,
            },
            { data:'idfp' },
            { data:'nama' },
            { data:'jabatan' },
        ],
    });
}
function dataTableTerlambat(idTable,urlTable) {
    $("#"+idTable).DataTable({
        serverSide:true,
        responsive:true,
        processing:true,
        oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   ",
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
        //load data
        ajax: {
            url: base_url+urlTable,
            type: 'POST',
        },

        order:[[2,'ASC']],
        columns:[
            {
                data:'no',
                searchable:false,
                orderable:false,
            },
            { data:'idfp' },
            { data:'nama' },
            { data:'jabatan' },
            // { data:'shift' },
            { data:'nama_jadwal' },
            { data:'masuk' },
            { data:'telat' },
        ],
    });
}

function dataTableKontrak(idTable,urlTable) {
    $("#"+idTable).DataTable({
        serverSide:true,
        responsive:true,
        processing:true,
        oLanguage: {
            sZeroRecords: "<center>Data tidak ditemukan</center>",
            sLengthMenu: "Tampilkan _MENU_ data   ",
            sSearch: "Cari data:",
            sInfo: "Menampilkan: _START_ - _END_ dari total: _TOTAL_ data",
            oPaginate: {
                sFirst: "Awal", "sPrevious": "Sebelumnya",
                sNext: "Selanjutnya", "sLast": "Akhir"
            },
        },
        //load data
        ajax: {
            url: base_url+urlTable,
            type: 'POST',
        },

        order:[[2,'ASC']],
        columns:[
            {
                data:'no',
                searchable:false,
                orderable:false,
            },
            { data:'idfp' },
            { data:'nama' },
            { data:'jabatan' },
            { data:'start_date' },
            { data:'expired_date' },
        ],
    });
}

function dataTableKontrakManual(idTable,urlTable) {
  $.post(base_url+urlTable,function(json) {
    if (json.status == true) {
      var tableData = "";
      if (json.data.length == 0) {
        tableData += '<tr>'+'<td colspan="6" style="text-align:center;">Data tidak ditemukan</td>'+

                     '</tr>'
      }
      else {
        $.each(json.data,function(key, val) {
            tableData += '<tr>'+'<td>'+(key+1)+'</td>'+
            '<td>'+val.idfp+'</td>'+
            '<td>'+val.nama+'</td>'+
            '<td>'+val.jabatan+'</td>'+
            '<td>'+val.start_date+'</td>'+
            '<td>'+val.expired_date+'</td>'+
            '</tr>'
        });
      }
        $('#'+idTable).find('tbody').html(tableData);
      }
    });
}



/*total karyawan modal*/
$("#totalKaryawanCount").parent().click(function() {
    $(".modal-title").html("Total Karyawan");
    $("#modalTotalKaryawan").modal("show");
});
// takerjadwal modal
$("#takterjadwalCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tabletakterjadwal").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/takterjadwal/"+valTglChart).load();
    }
    $(".modal-title").html("Jadwal Tidak Ditentukan");
    $("#modalTakterjadwal").modal("show");
});

/*hadir modal*/
$("#hadirCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableHadir").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/hadir/"+valTglChart).load();
    }
    $(".modal-title").html("Hadir");
    $("#modalHadir").modal("show");
});

/*tidak hadir modal*/
$("#mangkirCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableMangkir").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableTidakHadir/"+valTglChart).load();
    }
    $(".modal-title").html("Tidak Hadir / Absen");
    $("#modalMangkir").modal("show");
});

/*sakit modal*/
$("#sakitCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableSakit").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/sakit/"+valTglChart).load();
    }
    $(".modal-title").html("Sakit");
    $("#modalSakit").modal("show");
});

/*izin modal*/
$("#izinCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableIzin").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/izin/"+valTglChart).load();
    }
    $(".modal-title").html("Izin");
    $("#modalIzin").modal("show");
});

/*Cuti modal*/
$("#cutiCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableCuti").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/cuti/"+valTglChart).load();
    }
    $(".modal-title").html("Cuti");
    $("#modalCuti").modal("show");
});

/*off modal*/
// $("#offCount").parent().click(function() {
//     if (valTglChart != "") {
//         var table = $("#tableOff").DataTable();
//             table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensiOff/"+valTglChart).load();
//     }
//     $(".modal-title").html("OFF");
//     $("#modalOff").modal("show");
// });
/*Dinas modal*/
$("#dinasCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableDinas").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/dinas/"+valTglChart).load();
    }
    $(".modal-title").html("Perjalanan Dinas");
    $("#modalDinas").modal("show");
});

/*Tepat modal*/
$("#hadirTepatCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableHadirTepat").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/tepat/"+valTglChart).load();
    }
    $(".modal-title").html("Hadir Tepat Waktu");
    $("#modalHadirTepat").modal("show");
});
$("#hadirTerlambatCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableHadirTerlambat").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/terlambat/"+valTglChart).load();
    }
    $(".modal-title").html("Hadir Terlambat");
    $("#modalHadirTerlambat").modal("show");
});
$("#totalKehadiranCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableHadir").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensi/hadir/"+valTglChart).load();
    }
    $(".modal-title").html("Total kehadiran karyawan");
    $("#modalHadir").modal("show");
});

/*for modal table shift*/
$("#regularCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftRegular").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/regular/"+valTglChart).load();
    }
    $(".modal-title").html("REGULAR");
    $("#modalShiftRegular").modal("show");
});

$("#shiftPagiCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftPagi").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/pagi/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT PAGI");
    $("#modalShiftPagi").modal("show");
});

$("#shiftSoreCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftSore").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/sore/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT SORE");
    $("#modalShiftSore").modal("show");
});

$("#shiftMalamCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftMalam").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/malam/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT MALAM");
    $("#modalShiftMalam").modal("show");
});

$("#shiftPagi12Count").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftPagi12").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/pagi12/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT PAGI 12 JAM");
    $("#modalShiftPagi12").modal("show");
});

$("#shiftMalam12Count").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftMalam12").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/malam12/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT MALAM 12 JAM");
    $("#modalShiftMalam12").modal("show");
});

$("#shiftManualCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftManual").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranShift/manual/"+valTglChart).load();
    }
    $(".modal-title").html("SHIFT MANUAL");
    $("#modalShiftManual").modal("show");
});

$("#extraKerjaCount").parent().click(function() {
    if (valTglChart != "") {
        var table = $("#tableShiftExtra").DataTable();
            table.ajax.url(base_url+"dashboard/dataTableKehadiranAbsensiExtra/"+valTglChart).load();
    }
    $(".modal-title").html("EXTRA KERJA");
    $("#modalShiftExtra").modal("show");
});

$("#sisa1Bulan").parent().click(function() {
    $(".modal-title").html("Kontrak sisa 1 bulan");
    $("#modalKontrakSisa_1").modal("show");
});

$("#sisa2Bulan").parent().click(function() {
    $(".modal-title").html("Kontrak sisa 2 bulan");
    $("#modalKontrakSisa_2").modal("show");
});

$("#sisa3Bulan").parent().click(function() {
    $(".modal-title").html("Kontrak sisa 3 bulan");
    $("#modalKontrakSisa_3").modal("show");
});

$("#habisKontrakBulan").parent().click(function() {
    $(".modal-title").html("Habis Kontrak");
    $("#modalKontrakHabis").modal("show");
});
