const express = require("express");
const app = express();
app.use(express.json());
const CSVconverter = require("json-2-csv");
const convert = require("xml-js");
const axios = require("axios");
const FormData = require("form-data");
const cors = require("cors")

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static('public'));


// app.use(cors({
//   origin: "http://127.0.0.1:5500"
// })
// )

app.use(cors({
  origin: "http://127.0.0.1:3000/"
})
)

app.get("/consumer", (req, res) => {

  //return res.sendFile(__dirname + "/public/consumer.html")
  return res.send("hcttp://localhost/New_consumer/")
});


app.post("/publisher", function (req, res) {
  console.log(req.body);

  let xml = "";

  let pub_name = "";

  let top_name = "";

  let msg = "";

  let myData = req.body.jsonData;

  const formatInput = req.body.format;

  switch (formatInput) {
    case "xml":
      const Dataxml = req.body.xmlData;
      var options = { compact: true };
      var result = convert.xml2json(Dataxml, options);

      xml = JSON.parse(result);

      pub_name = xml.xml.pub_name._text;
      top_name = xml.xml.top_name._text;
      msg = xml.xml.msg._text;

      break;
    case "json":
      pub_name = myData.pub_name;
      top_name = myData.top_name;
      msg = myData.msg;
      console.log("this is json");
      break;
    case "csv":
      console.log(req.body.csvData);
      let dataCsv = req.body.csvData;
      dataCsv = dataCsv.replace(/\n/g, ',');
      const csvToArray = dataCsv.split(",");

      pub_name = csvToArray[3];
      top_name = csvToArray[4];
      msg = csvToArray[5];
      console.log("this is csv");
      break;

    case "tsv":
      console.log(req.body.tsvData);
      let dataTsv = req.body.tsvData;
      dataTsv = dataTsv.replace(/\n/g, "\t");
      const tsvToArray = dataTsv.split("\t");
      
      pub_name = tsvToArray[3];
      top_name = tsvToArray[4];
      msg = tsvToArray[5];
      console.log("this is tsv");
      break;

    default:
      console.log(`Please input a valid format`);
  }
  if(pub_name !="" && top_name != "" && msg != ""){
    const form = new FormData();
    // form.append("action", "put_msg");
    form.append("pub_name", pub_name);
    form.append("top_name", top_name);
    form.append("msg", msg);
    
    // Send form data with axios
    const response = axios.post("http://[::1]/esb-rest-api/put-msg", form, {
      headers: {
        ...form.getHeaders(),
        // Authentication: 'Bearer ...',
      },
    });
    
    res.send("Your message has been sent successfully");
  }
  else
    res.send("Message could not be sent.");

});

app.post("/consumer", function (req, res) {


  console.log(req.body);

  const form = new FormData();
  // form.append("action", "get_msg");
  form.append("top_name", req.body.top_name);
  form.append("top_last", req.body.last_topic);

  // Send form data with axios
  const response = axios.post("http://[::1]/esb-rest-api/get-msg", form, {
    headers: {
      ...form.getHeaders(),
      // Authentication: 'Bearer ...',
    },
  });
  let dataTest = "";

  response
    .then(function (result) {
      //console.log(result.data);

      const arrayData = result.data;

      const lastTopicIndex = arrayData.slice(-1)[0].top_last;

      if (req.body.last_topic > lastTopicIndex) {
        console.log("EROOR!!!");
      }

      dataTest = arrayData;

      const formatInput = req.body.format;

      switch (formatInput) {
        case "csv":
          CSVconverter.json2csv(arrayData, (err, csv) => {
            if (err) {
              throw err;
            }
            
            res.send(csv);
          });

          console.log("send CSV:"+csv);
          break;
        case "json":
          res.send(arrayData);

          console.log("send JSON"+arrayData);
          break;
        case "xml":
          const options = { compact: true, ignoreComment: true, spaces: 4 };
          const xml = convert.json2xml(arrayData, options);
          res.send(xml);
          console.log("send XML"+xml);
          break;
          case "tsv":
          CSVconverter.json2csv(arrayData, (err, csv) => {
            if (err) {
              throw err;
            }
            tsv = csv.replace(/,/g, '\t');
            res.send(tsv);
          });

          console.log("send TSV"+tsv);
          break;
        default:
          console.log(`Sorry, something went worng!.`);
      }

    })
    .catch((err) => {
      res.send("No new messge");
      console.error("No last messge");
      console.log(err);
    })
    .finally(() => {
      const lastTopicIndex = dataTest.slice(-1)[0].top_last;

      const form = new FormData();
      // form.append("action", "put_del");
      form.append("top_name", req.body.top_name);
      form.append("con_name", req.body.con_name);
      form.append("top_last", lastTopicIndex);

      // ### Send last topic count to delivered table
      const response = axios.post("http://[::1]/esb-rest-api/put-del", form, {
        headers: {
          ...form.getHeaders(),
          // Authentication: 'Bearer ...',
        },
      });

      console.log("message queue was successfully sent to consumer!");
    });
});


app.listen(3000, (err) => {
  if(err){
      console.log(err);
  }
  else{
      console.log("Listening on port 3000");
      console.log("ESB is configured...");
  }
});