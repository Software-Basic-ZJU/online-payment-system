var xmlHttp
var obj = {};
obj.CONST_A = "null"
obj.CONST_B = "null"
obj.CONST_C = "null"
obj.CONST_D = "order_id"

function change1(c1)
{
    obj.CONST_A = c1
    showResult(obj.CONST_A, obj.CONST_B, obj.CONST_C, obj.CONST_D)
}
function change2(c2)
{
    if (c2 == ""){
        c2 = "null";}
    obj.CONST_B = c2
    showResult(obj.CONST_A, obj.CONST_B, obj.CONST_C, obj.CONST_D)
}
function change3(c3)
{
    if(c3.value == "递增")
        c3.value = "递减"
    else
        c3.value = "递增"
    obj.CONST_C = c3.value
    obj.CONST_D = c3.id
    showResult(obj.CONST_A, obj.CONST_B, obj.CONST_C, obj.CONST_D)
}

function load()
{
    showResult(obj.CONST_A, obj.CONST_B, obj.CONST_C, obj.CONST_D)
}
function showResult(str1, str2, str3, str4)
{
    xmlHttp = GetXmlHttpObject()
    if (xmlHttp == null)
    {
        alert("Browser does not support HTTP Request!")
        return
    }

    var url = "./php/search.php"
    url = url + "?p=" + str1 + "&" + "q=" + str2 + "&" + "r=" + str3 + "&" + "s=" + str4
    url = url + "&sid=" + Math.random()
    xmlHttp.onreadystatechange = stateChanged
    xmlHttp.open("GET", url, true)
    xmlHttp.send(null)
}

function stateChanged()
{
    if (xmlHttp.readyState == 4 || xmlHttp.readyState == "complete"){
        document.getElementById("liveSearch").innerHTML = xmlHttp.responseText;}
}
function GetXmlHttpObject()
{
    var xmlHttp = null;
    try{
        xmlHttp = new XMLHttpRequest();}
        catch (e){
            try{
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");}
            catch (e){
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");}}
    return xmlHttp;
}