/**
 * Created by CTPM on 05/04/2017.
 */

var hostName = window.location.hostname;

if(window.location.hostname == 'dev2.overneti.com.br')
{

    var basePath = "http://dev2.overneti.com.br/overminsync";

}
else if(window.location.hostname == 'localhost')
{

    var basePath = "http://localhost/overminsync";

}

console.log("HostName: " + hostName);
console.log("BasePath: " + basePath);