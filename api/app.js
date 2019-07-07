const express = require('express')
const app = express()
const mysql = require('mysql')
const bodyParser = require('body-parser')
const port = 3000

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
              res.json({status:true,message:"login success",email:resdata[0].email})
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

app.post('/userreport',(req,res)=>{
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


      var sqldat = "insert into credit_card (card_type,card_number,card_cvv,card_month,card_billing,card_year,card_name,card_user,status,limits,verified) values (?,?,?,?,?,?,?,?,?,?,?)"
      pool.query(sqldat,[type,number,cvv,month,billing,year,name,username,"1",limit,"1"],(err,resdata)=>{
        if(err){
          console.log(err);
        }
        else{
          res.json({status:true,message:"login success",resdata})
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
        var sqldat = "delete from credit_card where card_id = ? and card_user=? "
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

app.get('/transaction',(req,res)=>{

        var sqldat = "select * from transaction"
        pool.query(sqldat,[],(err,resdata)=>{
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
        var sqldat = "select cart.product_id,cart_id,qty,p.product_name,p.product_image,total from cart join product as p on p.product_id = cart.product_id where cart.status=0 and id_user in (select id_user from user where username = ?)"
        pool.query(sqldat,[username],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
              res.json({status:true,message:"success get cart",data:resdata});


          }
        })
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

  var sqldat = "select * from cart where status=0 and id_user in (select id_user from user where username = ?)"
  pool.query(sqldat,[cart_username],(err,resdata)=>{
    if(err){
      console.log(err);
    }
    else{
      if(resdata.length==1){

        console.log();
        var sqlpay = "insert into transaction (cart_id,user_id,transaction_amount,transaction_card,transaction_payment,transaction_process,ip_transaction,status) values (?,?,?,?,?,?,?,?)"
        pool.query(sqlpay,[resdata[0].cart_id,resdata[0].id_user,total,"",null,0,ipaddress,0],(err,respay)=>{
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
                res.json({status:true,message:"Processing"})
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
        pool.query(sqlpay,[string,resdata[0].id_user,total,"",null,0,ipaddress,0],(err,respay)=>{
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
                res.json({status:true,message:"Processing"})
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
})

app.post('/checkoutn',(req,res)=>{
  var cart_username = req.body.username
  var ipaddress = req.body.ipaddress
  var total= req.body.amount
  var idcc= req.body.idcc

  var sqldat = "select * from cart where status=0 and id_user in (select id_user from user where username = ?)"
  pool.query(sqldat,[cart_username],(err,resdata)=>{
    if(err){
      console.log(err);
    }
    else{
      if(resdata.length==1){

        console.log();
        var sqlpay = "insert into transaction (cart_id,user_id,transaction_amount,transaction_card,transaction_payment,transaction_process,ip_transaction,status) values (?,?,?,?,?,?,?,?)"
        pool.query(sqlpay,[string,resdata[0].id_user,total,idcc,null,1,ipaddress,0],(err,respay)=>{
          if(err){
            console.log(err);
          }
          else{
            var sqladdpayment = "insert into payment(payment_amount,payment_card,payment_type,payment_status,status,id_user) values (?,?,?,?,?,?)"
            pool.query(sqladdpayment,[total,idcc,0,1,0,resdata[0].iduser],(err,respaids)=>{
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
                    var sqlgettrans = "select * from transaction where id_user=? and status = 0 order by create_at desc"
                    pool.query(sqlgettrans,[resdata[0].id_user],(err,resgettrans)=>{
                      if(err){
                        console.log(err);
                      }
                      else{
                        var sqlupdatetrans = "update transaction set transaction_payment=? where transaction_id=?"
                        pool.query(sqlupdatetrans,[resgetpay[0].payment_id,resgettrans[0].transaction_id],(err,resgetpay)=>{
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
                                res.json({status:true,message:"Processing"})
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
        pool.query(sqlpay,[string,resdata[0].id_user,total,idcc,null,1,ipaddress,0],(err,respay)=>{
          if(err){
            console.log(err);
          }
          else{
            var sqladdpayment = "insert into payment(payment_amount,payment_card,payment_type,payment_status,status,id_user) values (?,?,?,?,?,?)"
            pool.query(sqladdpayment,[total,idcc,0,1,0,resdata[0].id_user],(err,respaids)=>{
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
                        var sqlupdatetrans = "update transaction set transaction_payment=? where transaction_id=?"
                        pool.query(sqlupdatetrans,[resgetpay[0].payment_id,resgettrans[0].transaction_id],(err,resgetpay)=>{
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
                                res.json({status:true,message:"Processing"})
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
      else{
        res.json({status:false,message:"no data",resdata})
      }
    }
  })
})

app.post('/payment',(req,res)=>{
  var trans_id = req.body.trans_id;
        var sqldat = "select * from transaction"
        pool.query(sqldat,[],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"succes transactions",data:resdata})
          }
        })
})



app.listen(port, () => console.log(`listening on port ${port}!`))
