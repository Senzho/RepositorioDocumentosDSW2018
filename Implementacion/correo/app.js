var nodemailer = require('nodemailer');

var transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: 'marioolopez21@gmail.com',
    pass: 'aqui va una contrase�a'
  }
});

var mailOptions = {
  from: 'marioolopez21@gmail.com',
  to: 'maqueug@gmail.com',
  subject: 'Sending Email using Node.js',
  text: 'That was easy!'
};

transporter.sendMail(mailOptions, function(error, info){
  if (error) {
    console.log(error);
  } else {
    console.log('Email sent: ' + info.response);
  }
});