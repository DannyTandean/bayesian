const express = require('express')
const app = express()
const mysql = require('mysql')
const multer = require('multer')
const bodyParser = require('body-parser')
const port = 3000

var facestorage = multer({
  dest: function (req, file, cb) {
    cb(null, './report/')
  },
 })

 function updateproduct(iduser) {
   var sqlceku = "select * from user where id_user=?"
   pool.query(sqlceku,[iduser],(err,resceku)=>{
     if(err){
       console.log(err);
     }
     else{
       if(resceku.length==0){
         console.log("no data");
       }
       else{
         var trueproduct = 0 ;
         if(resceku[0].product_id==0 ||resceku[0].product_id==null){
           trueproduct = 0;
           console.log("nothing");
         }
         else{
           trueproduct = resceku[0].product_id;
         }
         var sqlcek = "SELECT * FROM `transaction`  where user_id=? ORDER BY `transaction`.`create_at`  DESC"
         pool.query(sqlcek,[iduser],(err,rescek)=>{
           if(err){
             console.log(err);
           }
           else{
             if(rescek.length==0){
               console.log("no data");
             }
             else{
               if(rescek[0].cart_id.includes(",")){
                 console.log("includes");
                 var sqlup = "update user set product_id = ?,treshold=? where user_id=?"
                 pool.query(sqlup,[rescek[0].cart_id.split(",")[0],0,iduser],(err,resup)=>{
                       console.log(success);
                 })
               }
               else{
                 console.log("cart id - "+rescek[0].cart_id);
                 var products = rescek[0].cart_id.split(",");
                 var sqlcart = "select * from cart where cart_id=?"
                 pool.query(sqlcart,[products[0]],(err,rescart)=>{
                     if(err){
                       console.log(err);
                     }
                     else{
                       if(rescart.length==0){

                       }
                       else{
                        console.log("versus" + rescart[0].product_id+ " "+rescek[0].product_id);
                         if(rescart[0].product_id==resceku[0].product_id){
                           var sqlup = "update user set product_id = ?,treshold=treshold+1 where id_user=?"
                           pool.query(sqlup,[rescart[0].product_id,iduser],(err,resup)=>{
                             if(err){
                               console.log(err);
                             }
                               console.log("done1");
                           })
                         }
                         else{
                           if(trueproduct==0){
                             var sqlup = "update user set user.product_id = ?,treshold=1 where user.id_user=?"
                             pool.query(sqlup,[rescart[0].product_id,iduser],(err,resup)=>{
                               if(err){
                                 console.log(err);
                               }
                                 console.log("done2");
                             })
                           }
                           else{

                             var sqlup = "update user join product as p on p.product_id=user.product_id set user.product_id = ?,user.status=if(treshold<(20000000/p.product_price),1,2),user.treshold=? where user.id_user=?"
                             console.log(sqlup);
                             console.log(rescart[0].product_id+" aw "+iduser);
                             pool.query(sqlup,[rescart[0].product_id,1,iduser],(err,resup)=>{
                               if(err){
                                 console.log(err);
                               }
                                 console.log("done2");
                             })
                           }

                         }
                       }
                     }
                 })

               }
             }
           }
         })
       }
     }
   })
   var sqlcek = "select * from transaction where user_id=?"
   pool.query(sqlcek,[iduser],(err,rescek)=>{
     if(err){
       console.log(err);
     }
     else{
       if(rescek.length==0){
         console.log("no data");
       }
       else{

       }
     }
   })
 }


app.use(bodyParser.urlencoded({ extended: true }))
// parse application/json
app.use(bodyParser.json())

const pool = mysql.createPool({
    host     : "localhost",
    user     : "root",
    password : "",
    database : "bayesian",
    port     : 3306
});

app.get('/', (req, res) => res.send('Hello World!'))

app.post('/login',(req,res)=>{
  var username = req.body.username;
  var password = req.body.password;
  var keys = req.body.keys;
        var sqldat = "select * from user where username = ? and password = ?"
         pool.query(sqldat,[username,password],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            if(resdata.length==0){
              res.json({status:false,message:"Username or password doesn't match any record",resdata})
            }
            else{
              if(resdata[0].block==1){
                res.json({status:false,message:"Username is blocked",resdata})
              }
              else{
                var sqlupdate = "update user set key_user=? where username = ? and password = ?"
                 pool.query(sqlupdate,[keys,username,password],(err,resdatas)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    res.json({status:true,message:"login success",email:resdata[0].email})
                  }
                })
              }

            }

          }
        })
})

app.post('/getreport',(req,res)=>{
  var username = req.body.username;
        var sqldat = "select * from user where username = ? and password = ?"
         pool.query(sqldat,[username,password],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            if(resdata.length==0){
              res.json({status:false,message:"Username or password doesnt match any record",resdata})
            }
            else{
              var sqlupdate = "select * from report where user_id=?"
               pool.query(sqlupdate,[resdata[0].user_id],(err,resdatas)=>{
                if(err){
                  console.log(err);
                }
                else{
                  res.json({status:true,message:"login success",email:resdata[0].email})
                }
              })
            }

          }
        })
})

app.post('/register',(req,res)=>{
  var username = req.body.username;
  var password = req.body.password;
  var email = req.body.email;
  var gender = req.body.gender;
  var nama = req.body.nama;
  var hp = req.body.hp;
  var limit = req.body.limit;
    var check = "select * from user where username = ?"
     pool.query(check,[username],(err,rescheck)=>{
        if(err){
          console.log(err);
        }
        else {
          if(rescheck.length==0){
            var sqldat = "insert into user (email,jenis_kelamin,nama,no_telp,password,transaction_limit,username) values (?,?,?,?,?,?,?)"
             pool.query(sqldat,[email,gender,nama,hp,password,limit,username],(err,resdata)=>{
              if(err){
                console.log(err);
              }
              else{
                res.json({status:true,message:"register success",resdata})
              }
            })
          }
          else{
            res.json({status:false,message:"username is already registered"})
          }
        }
     })

})

app.post('/register/card',(req,res)=>{
  var username = req.body.username;
  var password = req.body.password;
  var cvv = req.body.cvv;
  var month = req.body.month;
  var name = req.body.cardname;
  var number = req.body.number;
  var billing = req.body.billing
  var type = req.body.type;
  var year = req.body.year;
  var limit = req.body.limit;

    var check = "select * from credit_card where card_number=? and card_user=?"
    pool.query(check,[number,username],(err,rescheck)=>{
      if(err){
        console.log(err);
      }
      else{
        if(rescheck.length==1){
          res.json({status:false,message:"Card Already Exist"})
        }
        else{
          var sqldat = "insert into credit_card (card_type,card_number,card_cvv,card_month,card_billing,card_year,card_name,card_user,status,limits,verified) values (?,?,?,?,?,?,?,?,?,?,?)"
          pool.query(sqldat,[type,number,cvv,month,billing,year,name,username,"1",limit,"1"],(err,resdata)=>{
            if(err){
              console.log(err);
            }
            else{
              res.json({status:true,message:"login success",resdata})
            }
          })
        }

      }
    })


})

app.post('/creditcard',(req,res)=>{
    var username = req.body.username
        var sqldat = "select * from credit_card where status = 1 and card_user= ? "
        pool.query(sqldat,[username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            if(resdata.length==0){
              res.json({status:false,message:"fail Credit Card",data:resdata,total:resdata.length})
            }
            else{
              res.json({status:true,message:"success Creditcard",data:resdata,total:resdata.length})
            }

          }
        })
})

app.post('/deletecreditcard',(req,res)=>{
    var username = req.body.username
    var id = req.body.id;
        var sqldat = "update credit_card set status = 0 where card_id = ? and card_user=? "
        pool.query(sqldat,[id,username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
              res.json({status:true,message:"success Creditcard",data:resdata,total:resdata.length})

          }
        })
})

app.get('/products',(req,res)=>{

        var sqldat = "select * from product where status = 1 "
        pool.query(sqldat,[],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"success products",data:resdata,total:resdata.length})
          }
        })
})

app.post('/transaction',(req,res)=>{
        var username = req.body.username
        var sqldat = "select * from transaction where user_id in (select id_user from user where username = ?)"
        pool.query(sqldat,[username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"succes transactions",data:resdata,total:resdata.length})
          }
        })
})

app.post('/cart',(req,res)=>{
  var username = req.body.username;
  var number = req.body.number;
  if(number==0 || number == null){
    var sqldat = "select cart.product_id,cart_id,qty,p.product_name,p.product_image,total from cart join product as p on p.product_id = cart.product_id where cart.status=0 and id_user in (select id_user from user where username = ?)"
    pool.query(sqldat,[username],(err,resdata)=>{
      if(err){
        console.log(err);
      }
      else{
          res.json({status:true,message:"success get cart",data:resdata});


      }
    })
  }
  else{
    var sqldat = "select cart.product_id,cart_id,qty,p.product_name,p.product_image,total from cart join product as p on p.product_id = cart.product_id  where (select cart_id from transaction where transaction_id=?) like concat('%',cart.cart_id,'%') "
    pool.query(sqldat,[number],(err,resdata)=>{
      if(err){
        console.log(err);
      }
      else{
          res.json({status:true,message:"success get cart",data:resdata});


      }
    })
  }

})

app.post('/cart/add',(req,res)=>{
  var product_id = req.body.id;
  var username = req.body.username;
  var price = req.body.price;

        var sqldat = "select * from cart where cart_id = ? and status=0 and id_user in (select id_user from user where username = ?)"
        pool.query(sqldat,[product_id,username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            if(resdata.length==1){
              var sqldat = "update cart set qty = qty+1,total = ?*qty where cart_id = ? and id_user in (select id_user from user where username = ?)"
              pool.query(sqldat,[price,product_id,username],(err,resdata)=>{
                if(err){
                  console.log(err);
                }
                else{
                  res.json({status:true,message:"added cart",resdata})
                }
              })
            }
            else{
              var sqldat = "insert into cart (id_user,product_id,qty,total,cart_checkout,status) values ((select id_user from user where username = ?),?,?,?,?,?)"
              pool.query(sqldat,[username,product_id,"1",price,"0","0"],(err,resdata)=>{
                if(err){
                  console.log(err);
                }
                else{
                  res.json({status:true,message:"added to cart",resdata})
                }
              })
            }
          }
        })
})

app.post('/cart/minus',(req,res)=>{
  var product_id = req.body.id;
  var username = req.body.username;
  var price = req.body.price;

        var sqldat = "select * from cart where cart_id = ? and status=0 and id_user in (select id_user from user where username = ?)"
        pool.query(sqldat,[product_id,username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            if(resdata.length==1){
              if(resdata[0].qty=1){
                var sqldat = "update cart set qty = qty-1,total = ?*qty where cart_id = ? and id_user in (select id_user from user where username = ?)"
                pool.query(sqldat,[price,product_id,username],(err,resdata)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    res.json({status:true,message:"substracted product",resdata})
                  }
                })
              }
              else{
                var sqldat = "delete from cart where product_id=? and id_user in (select id_user from user where username = ?)"
                pool.query(sqldat,[price,product_id,username],(err,resdata)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    res.json({status:true,message:"removed from cart",resdata})
                  }
                })
              }

          }
          else{
            res.json({status:false ,message:"product not found",resdata})
          }
        }
    })
})

app.post('/cart/remove',(req,res)=>{
  var product_id = req.body.id;
  var username = req.body.username;
  var sqldat = "delete from cart where cart_id = ? and id_user in (select id_user from user where username = ?)"
  pool.query(sqldat,[product_id,username],(err,resdata)=>{
    if(err){
      console.log(err);
    }
    else{
      res.json({status:true,message:"removed from cart",resdata})
    }
  })
})

app.post('/addtocart',(req,res)=>{
  var product_id = req.body.id;
  var username = req.body.username;
  var price = req.body.price;

  var sqldat = "select * from cart where product_id = ? and status=0 and id_user in (select id_user from user where username = ?)"
  pool.query(sqldat,[product_id,username],(err,resdata)=>{
    if(err){
      console.log(err);
    }
    else{
      if(resdata.length==1){
        var sqldat = "update cart set qty = qty+1,total = ?*qty where product_id=? and id_user in (select id_user from user where username = ?)"
        pool.query(sqldat,[price,product_id,username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"added cart",resdata})
          }
        })
      }
      else{
        var sqldat = "insert into cart (id_user,product_id,qty,total,cart_checkout,status) values ((select id_user from user where username = ?),?,?,?,?,?)"
        pool.query(sqldat,[username,product_id,"1",price,"0","0"],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"added to cart",resdata})
          }
        })
      }
    }
  })
})

app.post('/checkout',(req,res)=>{
  var cart_username = req.body.username
  var ipaddress = req.body.ipaddress
  var total= req.body.amount

  var sqlceku = "select * from user where username=?"
  pool.query(sqlceku,[cart_username],(err,resceku)=>{

    if(resceku.length==0){

    }
    else{
      var status = 0 ;
      if(resceku[0].status==2){
        status = 2;
      }
      else{
        status = 0;
      }
      var sqldat = "select * from cart where status=0 and id_user in (select id_user from user where username = ?)"
      pool.query(sqldat,[cart_username],(err,resdata)=>{
        if(err){
          console.log(err);
        }
        else{
          if(resdata.length==1){

            console.log();
            var sqlpay = "insert into transaction (cart_id,user_id,transaction_amount,transaction_card,transaction_payment,transaction_process,ip_transaction,status) values (?,?,?,?,?,?,?,?)"
            pool.query(sqlpay,[resdata[0].cart_id,resdata[0].id_user,total,"",null,0,ipaddress,status],(err,respay)=>{
              if(err){
                console.log(err);
              }
              else{
                var sqlcleancart = "update cart set status = 1 where id_user=?"
                pool.query(sqlcleancart,[resdata[0].id_user],(err,restry)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    var sqlselip = "select * from ip where user_id = ? and ip_address = ?"
                    pool.query(sqlselip,[resdata[0].id_user,ipaddress],(err,resselip)=>{
                      if (err) {
                        console.log(err);
                      }
                      else {
                        var sqlselip = "select * from ip where user_id = ? and ip_address = ?"
                        pool.query(sqlselip,[resdata[0].id_user,ipaddress],(err,resselip)=>{
                          if (err) {
                            console.log(err);
                          }
                          else {
                            if (resselip.length == 0) {
                              var sqlip = "insert into ip (user_id,ip_address,tipe,status) values (?,?,?,?)"
                              pool.query(sqlip,[resdata[0].id_user,ipaddress,0,0],(err,resIp)=>{
                                if (err) {
                                  console.log(err);
                                }
                                else {
                                  res.json({status:true,message:"Processing"})
                                }
                              })
                            }
                            else {
                              console.log("data ip sudah ada");
                              res.json({status:true,message:"Processing"})
                            }
                          }
                        })
                      }
                    })
                  }
                })
              }
            })
          }
          else if(resdata.length>1){
            var string="";
            for(var i=0; i < resdata.length;i++){
              if(i==0){
                string = resdata[i].cart_id ;
              }
              else{
                string = string + "," + resdata[i].cart_id;
              }
            }
            var sqlpay = "insert into transaction (cart_id,user_id,transaction_amount,transaction_card,transaction_payment,transaction_process,ip_transaction,status) values (?,?,?,?,?,?,?,?)"
            pool.query(sqlpay,[string,resdata[0].id_user,total,"",null,0,ipaddress,status],(err,respay)=>{
              if(err){
                console.log(err);
              }
              else{
                var sqlcleancart = "update cart set status = 1 where id_user=?"
                pool.query(sqlcleancart,[resdata[0].id_user],(err,restry)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    var sqlselip = "select * from ip where user_id = ? and ip_address = ?"
                    pool.query(sqlselip,[resdata[0].id_user,ipaddress],(err,resselip)=>{
                      if (err) {
                        console.log(err);
                      }
                      else {
                        if (resselip.length == 0) {
                          var sqlip = "insert into ip (user_id,ip_address,tipe,status) values (?,?,?,?)"
                          pool.query(sqlip,[resdata[0].id_user,ipaddress,0,status],(err,resIp)=>{
                            if (err) {
                              console.log(err);
                            }
                            else {
                              res.json({status:true,message:"Processing"})
                            }
                          })
                        }
                        else {
                          console.log("data ip sudah ada");
                          res.json({status:true,message:"Processing"})
                        }
                      }
                    })
                  }
                })
              }
            })
          }
          else{
            res.json({status:false,message:"no data",resdata})
          }
        }
      })
    }


  })
})

app.post('/checkoutn',(req,res)=>{
  var cart_username = req.body.username
  var ipaddress = req.body.ipaddress
  var total= req.body.amount
  var idcc= req.body.idcc

  var sqlceku = "select * from user where username=?"
  pool.query(sqlceku,[cart_username],(err,resceku)=>{
    if(resceku.length==0){

    }
    else{
      var status = 0;
      if(resceku[0].status==2){
        status = 2;
      }
      else{
        status = 0;
      }
      var sqldat = "select * from cart where status=0 and id_user in (select id_user from user where username = ?)"
      pool.query(sqldat,[cart_username],(err,resdata)=>{
        if(err){
          console.log(err);
        }
        else{
          if(resdata.length>0){
            var string = "";
            if(resdata.length==1){
              console.log();
              string = resdata[0].cart_id;
            }
            else if(resdata.length>1){
              for(var i=0; i < resdata.length;i++){
                if(i==0){
                  string = resdata[i].cart_id ;
                }
                else{
                  string = string + "," + resdata[i].cart_id;
                }
              }
            }
            var sqlpay = "insert into transaction (cart_id,user_id,transaction_amount,transaction_card,transaction_payment,transaction_process,ip_transaction,status) values (?,?,?,?,?,?,?,?)"
            pool.query(sqlpay,[string,resdata[0].id_user,total,idcc,null,1,ipaddress,status],(err,respay)=>{
              if(err){
                console.log(err);
              }
              else{
                var sqladdpayment = "insert into payment(payment_amount,payment_card,payment_type,payment_status,status,id_user,ip_payment) values (?,?,?,?,?,?,?)"
                pool.query(sqladdpayment,[total,idcc,0,1,status,resdata[0].id_user,ipaddress],(err,respaids)=>{
                  if(err){
                    console.log(err);
                  }
                  else{
                    var sqlgetpay = "select * from payment where id_user=? and status = 0 order by payment_period desc"
                    pool.query(sqlgetpay,[resdata[0].id_user],(err,resgetpay)=>{
                      if(err){
                        console.log(err);
                      }
                      else{
                        var sqlgettrans = "select * from transaction where user_id=? and status = 0 order by create_at desc"
                        pool.query(sqlgettrans,[resdata[0].id_user],(err,resgettrans)=>{
                          if(err){
                            console.log(err);
                          }
                          else{
                            var sqlcredit = "select * from credit_card where card_id = ?"
                            pool.query(sqlcredit,[idcc],(err,rescredit)=>{
                              if (err) {
                                console.log(err);
                              }
                              else {
                                if(rescredit.length==0){

                                }
                                else{
                                  var sqlcreditc = "select * from credit_card where card_number = ?"
                                  pool.query(sqlcreditc,[rescredit[0].card_number],(err,rescreditc)=>{
                                    if (err) {
                                      console.log(err);
                                    }
                                    else {
                                      var fraud = 1;
                                      console.log(rescreditc);
                                      for(var i = 0 ; i < rescreditc.length;i++){
                                        console.log(rescreditc[i].card_name+" desk "+resceku[0].nama);
                                        if((rescreditc[i].card_name.toLowerCase()).includes(resceku[0].nama.toLowerCase())){
                                          fraud= 1;
                                        }
                                        else{
                                          fraud=2;
                                          break;
                                        }
                                      }
                                      var sqlupdatetrans = "update transaction set transaction_payment=?,status=? where transaction_id=?"
                                      pool.query(sqlupdatetrans,[resgetpay[0].payment_id,fraud,resgettrans[0].transaction_id],(err,resgetpay)=>{
                                        if(err){
                                          console.log(err);
                                        }
                                        else{
                                          sqlgettrans = "select * from transaction where transaction_id=?"
                                          pool.query(sqlgettrans,[resgettrans[0].transaction_id],(err,resgettrans)=>{
                                            if(err){
                                              console.log(err);
                                            }
                                            else{
                                              var sqluppay = "update payment set status=? where payment_id=?"
                                              pool.query(sqluppay,[fraud,resgettrans[0].transaction_payment],(err,resspay)=>{
                                                if(err){
                                                  console.log(err);
                                                }
                                                else{
                                                  var sqlcleancart = "update cart set status = 1 where id_user=?"
                                                  pool.query(sqlcleancart,[resdata[0].id_user],(err,restry)=>{
                                                    if(err){
                                                      console.log(err);
                                                    }
                                                    else{
                                                      console.log("babi");
                                                      console.log(resceku[0].id_user);
                                                      updateproduct(resceku[0].id_user);
                                                      var sqlselip = "select * from ip where user_id = ? and ip_address = ?"
                                                      pool.query(sqlselip,[resdata[0].id_user,ipaddress],(err,resselip)=>{
                                                        if (err) {
                                                          console.log(err);
                                                        }
                                                        else {
                                                          if (resselip.length == 0) {
                                                            var sqlip = "insert into ip (user_id,ip_address,tipe,status) values (?,?,?,?)"
                                                            pool.query(sqlip,[resdata[0].id_user,ipaddress,0,0],(err,resIp)=>{
                                                              if (err) {
                                                                console.log(err);
                                                              }
                                                              else {
                                                                res.json({status:true,message:"Processing"})
                                                              }
                                                            })
                                                          }
                                                          else {
                                                            console.log("data ip sudah ada");
                                                            res.json({status:true,message:"Processing"})
                                                          }
                                                        }
                                                      })
                                                    }
                                                  })
                                                }
                                              })
                                            }
                                          })


                                        }
                                      })


                                    }
                                  })
                                }
                              }
                            })

                          }
                        })
                      }
                    })
                  }
                })
              }
            })
          }
          else{
            res.json({status:false,message:"no data",resdata})
          }
        }
      })
    }

  })


})

app.post('/checkoutp',(req,res)=>{
  var trans_id = req.body.trans_id
  var cart_username = req.body.username
  var ipaddress = req.body.ipaddress
  var total= req.body.amount
  var idcc= req.body.idcc

  var user = "select id_user from user where username = ?"
  pool.query(user,[cart_username],(err,resuser)=>{
    if(err){
      console.log(err);
    }
    else{

      var status = 0;
      if(resuser[0].status==2){
        status = 2;
      }
      else{
        status = 0;
      }

      var sqladdpayment = "insert into payment(payment_amount,payment_card,payment_type,payment_status,status,id_user,ip_payment) values (?,?,?,?,?,?,?)"
      pool.query(sqladdpayment,[total,idcc,0,1,status,resuser[0].id_user,ipaddress],(err,respaids)=>{
        if(err){
          console.log(err);
        }
        else{
          var sqlgetpay = "select * from payment where id_user=? and status = 0 order by payment_period desc"
          pool.query(sqlgetpay,[resuser[0].id_user],(err,resgetpay)=>{
            if(err){
              console.log(err);
            }
            else{
              var sqlgettrans = "select * from transaction where transaction_id=? and status = 0 order by create_at desc"
              pool.query(sqlgettrans,[trans_id],(err,resgettrans)=>{
                if(err){
                  console.log(err);
                }
                else{
                  var sqlcredit = "select * from credit_card where card_id = ?"
                  pool.query(sqlcredit,[idcc],(err,rescredit)=>{
                    if (err) {
                      console.log(err);
                    }
                    else {
                      if(rescredit.length==0){

                      }
                      else{
                        var sqlcreditc = "select * from credit_card where card_number = ?"
                        pool.query(sqlcreditc,[rescredit[0].card_number],(err,rescreditc)=>{
                          if (err) {
                            console.log(err);
                          }
                          else {
                              var fraud = 1;
                              for(var i = 0 ; i < rescreditc.length;i++){
                                if(rescredit[i].cardname.toLowerCase().includes(resuser[0].nama.toLowerCase())){
                                  fraud=1;
                                }
                                else{
                                  fraud=2;
                                  break;
                                }
                              }


                              var sqlupdatetrans = "update transaction set transaction_payment=?,status=?,transaction_process=1 where transaction_id=?"
                              pool.query(sqlupdatetrans,[resgetpay[0].payment_id,fraud,resgettrans[0].transaction_id],(err,resgetpay)=>{
                                if(err){
                                  console.log(err);
                                }
                                else{
                                  console.log("pig");
                                  console.log(resuser[0].id_user);
                                  updateproduct(resuser[0].id_user);
                                  var sqlselip = "select * from ip where user_id = ? and ip_address = ?"
                                  pool.query(sqlselip,[resuser[0].id_user,ipaddress],(err,resselip)=>{
                                    if (err) {
                                      console.log(err);
                                    }
                                    else {
                                      if (resselip.length == 0) {
                                        var sqlip = "insert into ip (user_id,ip_address,tipe,status) values (?,?,?,?)"
                                        pool.query(sqlip,[resuser[0].id_user,ipaddress,1,0],(err,resIp)=>{
                                          if (err) {
                                            console.log(err);
                                          }
                                          else {
                                            res.json({status:true,message:"Processing"})
                                          }
                                        })
                                      }
                                      else {
                                        console.log("data ip sudah ada");
                                        res.json({status:true,message:"Processing"})
                                      }
                                    }
                                  })
                                }
                              })
                          }
                        })
                      }
                    }
                  })
                }
              })
            }
          })
        }
      })
    }
  })
})

app.post('/payment',(req,res)=>{
  var trans_id = req.body.trans_id;
        var sqldat = "select c.card_number,p.payment_id,t.transaction_amount,p.payment_period from transaction as t join payment as p on p.payment_id=t.transaction_payment join credit_card as c on c.card_id = p.payment_card  where t.transaction_id = ? "
        pool.query(sqldat,[trans_id],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"succes transactions",data:resdata})
          }
        })
})

app.post("/userreporttest",(req,res)=>{
     var em = req.body.email;
     var fn = req.body.fullname;
     var msg = req.body.message;
     var type = req.body.type;
     var username = req.body.username;
     console.log(req.file);
       var getuser = "select * from user where username = ?"
       pool.query(getuser,[username],(err,datauser)=>{
           if(err){
             console.log(err);
           }
           else{
             if(datauser.length==0){
               res.json({status:false,message:"data not found"});
             }
             else{
               var getpembagi = "insert into reporttest(user_id,email,type,report_message) values (?,?,?,?)"
               pool.query(getpembagi,[datauser[0].id_user,em,type,msg],(err,datanumber)=>{
                   if(err){
                     console.log(err);
                   }
                   else{
                     if(datanumber.length==0){
                       res.json({status:false,message:"data not found"});
                     }
                     else{
                       res.json({status:true,message:"Success Reporting"});
                     }
                   }
               })
             }
           }
       })




})

app.post("/userreport",facestorage.single('image'),(req,res)=>{
     var tr = req.body.transaction;
     var pa = req.body.payment;
     var pr = req.body.product;
     var em = req.body.email;
     var fn = req.body.fullname;
     var cc = req.body.creditcard;
     var msg = req.body.message;
     var email = req.body.email;
     var fullname = req.body.fullname;
     var username = req.body.username;
     console.log(req.file);
     if (req.file) {

       var getuser = "select * from user where username = ?"
       pool.query(getuser,[username],(err,datauser)=>{
           if(err){
             log.info(err);
           }
           else{
             if(datauser.length==0){
               res.json({status:false,message:"data not found"});
             }
             else{
               var getpembagi = "insert into report (user_id,report_user,report_product,report_payment,report_transaction,report_creditcard,report_message,dir,status,email,fullname) values (?,?,?,?,?,?,?,?,?,?,?)"
               pool.query(getpembagi,[datauser[0].id_user,null,pr,pa,tr,cc,msg,req.file.path,"0",email,fullname],(err,datanumber)=>{
                   if(err){
                     log.info(err);
                   }
                   else{
                     if(datanumber.length==0){
                       res.json({status:false,message:"data not found"});
                     }
                     else{
                       res.json({status:true,message:"Success Reporting"});
                     }
                   }
               })
             }
           }
       })


     } else {
        res.json({status:false,message:"gagal face"});
     }



})



app.listen(port, () => console.log(`listening on port ${port}!`))
