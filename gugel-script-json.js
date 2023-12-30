function doGet(e){
 
 // ganti dengan url spreadsheet
 var ss = SpreadsheetApp.openByUrl("https://docs.google.com/spreadsheets/d/1Y4v0AQY8VbKryxxbKzOqT9wIV5i8Z1TDJVxJoBgHCGQ/edit#gid=112277800");
 
// nama sheet, ganti sesuai nama sheet yoh
 var sheet = ss.getSheetByName("OPD");
 
 return getUsers(sheet); 
 
}
 
 
function getUsers(sheet){
  var jo = {};
  var dataArray = [];
 
// ambil data di mulai Row ke 2 , dr kolom 1 column sd data terakhir dan kolom terakhi alias all data
  var rows = sheet.getRange(6,1,sheet.getLastRow()-1, sheet.getLastColumn()).getValues();
 
  for(var i = 0, l= rows.length; i<l ; i++){
    var dataRow = rows[i];
    var record = {};
    record['no'] = dataRow[0];
    record['nopd'] = dataRow[1];
    record['opd'] = dataRow[2];
    record['murni'] = dataRow[3];
    record['geser'] = dataRow[4];
    record['realisasi'] = dataRow[5];
    record['prosentase'] = dataRow[6];
    dataArray.push(record);
 
  }  
 
  jo.datanya = dataArray;
 
  var result = JSON.stringify(jo);
 
  return ContentService.createTextOutput(result).setMimeType(ContentService.MimeType.JSON);
 
}
