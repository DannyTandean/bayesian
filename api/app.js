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
  var type = req.body.type;
  var year = req.body.year;

        var sqldat = "insert into credit_card (card_type,card_number,card_cvv,card_month,card_year,card_name,card_user,status,verified) values (?,?,?,?,?,?,?,?,?)"
        pool.query(sqldat,[type,number,cvv,month,year,name,username,"1","1"],(err,resdata)=>{
          if(err){
            console.log(err);
          }
          else{
            res.json({status:true,message:"login success",resdata})
          }
        })
})

app.get('/products',(req,res)=>{

        var sqldat = "select * from product"
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
            res.json({status:true,message:"succes transactions",data:resdata})
          }
        })
})

app.post('/cart',(req,res)=>{
  var username = req.body.username;
        var sqldat = "select cart.product_id,cart_id,qty,p.product_name,p.product_image,total from cart join product as p on p.product_id = cart.product_id where id_user in (select id_user from user where username = ?)"
        console.log(username);
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

        var sqldat = "select * from cart where cart_id = ? and id_user in (select id_user from user where username = ?)"
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

        var sqldat = "select * from cart where cart_id = ? and id_user in (select id_user from user where username = ?)"
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

  var sqldat = "select * from cart where product_id = ? and id_user in (select id_user from user where username = ?)"
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
