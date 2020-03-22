var J2000=2451545.0,JulianCentury=36525.0,JulianMillennium=(JulianCentury*10),AstronomicalUnit=149597870.0,TropicalYear=365.24219878;function astor(a)
{return a*(Math.PI/(180.0*3600.0))}
function dtr(d)
{return(d*Math.PI)/180.0}
function rtd(r)
{return(r*180.0)/Math.PI}
function fixangle(a)
{return a-360.0*(Math.floor(a/360.0))}
function fixangr(a)
{return a-(2*Math.PI)*(Math.floor(a/(2*Math.PI)))}
function dsin(d)
{return Math.sin(dtr(d))}
function dcos(d)
{return Math.cos(dtr(d))}
function mod(a,b)
{return a-(b*Math.floor(a/b))}
function amod(a,b)
{return mod(a-1,b)+1}
function jhms(j){var ij;j+=0.5;ij=((j-Math.floor(j))*86400.0)+0.5;return new Array(Math.floor(ij/3600),Math.floor((ij/60)%60),Math.floor(ij%60))}
var Weekdays=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");function jwday(j)
{return mod(Math.floor((j+1.5)),7)}
var oterms=new Array(-4680.93,-1.55,1999.25,-51.38,-249.67,-39.05,7.12,27.87,5.79,2.45);function obliqeq(jd)
{var eps,u,v,i;v=u=(jd-J2000)/(JulianCentury*100);eps=23+(26/60.0)+(21.448/3600.0);if(Math.abs(u)<1.0){for(i=0;i<10;i++){eps+=(oterms[i]/3600.0)*v;v*=u}}
return eps}
var nutArgMult=new Array(0,0,0,0,1,-2,0,0,2,2,0,0,0,2,2,0,0,0,0,2,0,1,0,0,0,0,0,1,0,0,-2,1,0,2,2,0,0,0,2,1,0,0,1,2,2,-2,-1,0,2,2,-2,0,1,0,0,-2,0,0,2,1,0,0,-1,2,2,2,0,0,0,0,0,0,1,0,1,2,0,-1,2,2,0,0,-1,0,1,0,0,1,2,1,-2,0,2,0,0,0,0,-2,2,1,2,0,0,2,2,0,0,2,2,2,0,0,2,0,0,-2,0,1,2,2,0,0,0,2,0,-2,0,0,2,0,0,0,-1,2,1,0,2,0,0,0,2,0,-1,0,1,-2,2,0,2,2,0,1,0,0,1,-2,0,1,0,1,0,-1,0,0,1,0,0,2,-2,0,2,0,-1,2,1,2,0,1,2,2,0,1,0,2,2,-2,1,1,0,0,0,-1,0,2,2,2,0,0,2,1,2,0,1,0,0,-2,0,2,2,2,-2,0,1,2,1,2,0,-2,0,1,2,0,0,0,1,0,-1,1,0,0,-2,-1,0,2,1,-2,0,0,0,1,0,0,2,2,1,-2,0,2,0,1,-2,1,0,2,1,0,0,1,-2,0,-1,0,1,0,0,-2,1,0,0,0,1,0,0,0,0,0,0,1,2,0,-1,-1,1,0,0,0,1,1,0,0,0,-1,1,2,2,2,-1,-1,2,2,0,0,-2,2,2,0,0,3,2,2,2,-1,0,2,2);var nutArgCoeff=new Array(-171996,-1742,92095,89,-13187,-16,5736,-31,-2274,-2,977,-5,2062,2,-895,5,1426,-34,54,-1,712,1,-7,0,-517,12,224,-6,-386,-4,200,0,-301,0,129,-1,217,-5,-95,3,-158,0,0,0,129,1,-70,0,123,0,-53,0,63,0,0,0,63,1,-33,0,-59,0,26,0,-58,-1,32,0,-51,0,27,0,48,0,0,0,46,0,-24,0,-38,0,16,0,-31,0,13,0,29,0,0,0,29,0,-12,0,26,0,0,0,-22,0,0,0,21,0,-10,0,17,-1,0,0,16,0,-8,0,-16,1,7,0,-15,0,9,0,-13,0,7,0,-12,0,6,0,11,0,0,0,-10,0,5,0,-8,0,3,0,7,0,-3,0,-7,0,0,0,-7,0,3,0,-7,0,3,0,6,0,0,0,6,0,-3,0,6,0,-3,0,-6,0,3,0,-6,0,3,0,5,0,0,0,-5,0,3,0,-5,0,3,0,-5,0,3,0,4,0,0,0,4,0,0,0,4,0,0,0,-4,0,0,0,-4,0,0,0,-4,0,0,0,3,0,0,0,-3,0,0,0,-3,0,0,0,-3,0,0,0,-3,0,0,0,-3,0,0,0,-3,0,0,0,-3,0,0,0);function nutation(jd)
{var deltaPsi,deltaEpsilon,i,j,t=(jd-2451545.0)/36525.0,t2,t3,to10,ta=new Array,dp=0,de=0,ang;t3=t*(t2=t*t);ta[0]=dtr(297.850363+445267.11148*t-0.0019142*t2+t3/189474.0);ta[1]=dtr(357.52772+35999.05034*t-0.0001603*t2-t3/300000.0);ta[2]=dtr(134.96298+477198.867398*t+0.0086972*t2+t3/56250.0);ta[3]=dtr(93.27191+483202.017538*t-0.0036825*t2+t3/327270);ta[4]=dtr(125.04452-1934.136261*t+0.0020708*t2+t3/450000.0);for(i=0;i<5;i++){ta[i]=fixangr(ta[i])}
to10=t/10.0;for(i=0;i<63;i++){ang=0;for(j=0;j<5;j++){if(nutArgMult[(i*5)+j]!=0){ang+=nutArgMult[(i*5)+j]*ta[j]}}
dp+=(nutArgCoeff[(i*4)+0]+nutArgCoeff[(i*4)+1]*to10)*Math.sin(ang);de+=(nutArgCoeff[(i*4)+2]+nutArgCoeff[(i*4)+3]*to10)*Math.cos(ang)}
deltaPsi=dp/(3600.0*10000.0);deltaEpsilon=de/(3600.0*10000.0);return new Array(deltaPsi,deltaEpsilon)}
function ecliptoeq(jd,Lambda,Beta)
{var eps,Ra,Dec;eps=dtr(obliqeq(jd));log+="Obliquity: "+rtd(eps)+"\n";Ra=rtd(Math.atan2((Math.cos(eps)*Math.sin(dtr(Lambda))-(Math.tan(dtr(Beta))*Math.sin(eps))),Math.cos(dtr(Lambda))));log+="RA = "+Ra+"\n";Ra=fixangle(rtd(Math.atan2((Math.cos(eps)*Math.sin(dtr(Lambda))-(Math.tan(dtr(Beta))*Math.sin(eps))),Math.cos(dtr(Lambda)))));Dec=rtd(Math.asin((Math.sin(eps)*Math.sin(dtr(Lambda))*Math.cos(dtr(Beta)))+(Math.sin(dtr(Beta))*Math.cos(eps))));return new Array(Ra,Dec)}
var deltaTtab=new Array(121,112,103,95,88,82,77,72,68,63,60,56,53,51,48,46,44,42,40,38,35,33,31,29,26,24,22,20,18,16,14,12,11,10,9,8,7,7,7,7,7,7,8,8,9,9,9,9,9,10,10,10,10,10,10,10,10,11,11,11,11,11,12,12,12,12,13,13,13,14,14,14,14,15,15,15,15,15,16,16,16,16,16,16,16,16,15,15,14,13,13.1,12.5,12.2,12,12,12,12,12,12,11.9,11.6,11,10.2,9.2,8.2,7.1,6.2,5.6,5.4,5.3,5.4,5.6,5.9,6.2,6.5,6.8,7.1,7.3,7.5,7.6,7.7,7.3,6.2,5.2,2.7,1.4,-1.2,-2.8,-3.8,-4.8,-5.5,-5.3,-5.6,-5.7,-5.9,-6,-6.3,-6.5,-6.2,-4.7,-2.8,-0.1,2.6,5.3,7.7,10.4,13.3,16,18.2,20.2,21.1,22.4,23.5,23.8,24.3,24,23.9,23.9,23.7,24,24.3,25.3,26.2,27.3,28.2,29.1,30,30.7,31.4,32.2,33.1,34,35,36.5,38.3,40.2,42.2,44.5,46.5,48.5,50.5,52.2,53.8,54.9,55.8,56.9,58.3,60,61.6,63,65,66.6);function deltat(year)
{var dt,f,i,t;if((year>=1620)&&(year<=2000)){i=Math.floor((year-1620)/2);f=((year-1620)/2)-i;dt=deltaTtab[i]+((deltaTtab[i+1]-deltaTtab[i])*f)}else{t=(year-2000)/100;if(year<948){dt=2177+(497*t)+(44.1*t*t)}else{dt=102+(102*t)+(25.3*t*t);if((year>2000)&&(year<2100)){dt+=0.37*(year-2100)}}}
return dt}
var EquinoxpTerms=new Array(485,324.96,1934.136,203,337.23,32964.467,199,342.08,20.186,182,27.85,445267.112,156,73.14,45036.886,136,171.52,22518.443,77,222.54,65928.934,74,296.72,3034.906,70,243.58,9037.513,58,119.81,33718.147,52,297.17,150.678,50,21.02,2281.226,45,247.54,29929.562,44,325.15,31555.956,29,60.93,4443.417,18,155.12,67555.328,17,288.79,4562.452,16,198.04,62894.029,14,199.76,31436.921,12,95.39,14577.848,12,287.11,31931.756,12,320.81,34777.259,9,227.73,1222.114,8,15.45,16859.074);JDE0tab1000=new Array(new Array(1721139.29189,365242.13740,0.06134,0.00111,-0.00071),new Array(1721233.25401,365241.72562,-0.05323,0.00907,0.00025),new Array(1721325.70455,365242.49558,-0.11677,-0.00297,0.00074),new Array(1721414.39987,365242.88257,-0.00769,-0.00933,-0.00006));JDE0tab2000=new Array(new Array(2451623.80984,365242.37404,0.05169,-0.00411,-0.00057),new Array(2451716.56767,365241.62603,0.00325,0.00888,-0.00030),new Array(2451810.21715,365242.01767,-0.11575,0.00337,0.00078),new Array(2451900.05952,365242.74049,-0.06223,-0.00823,0.00032));function equinox(year,which)
{var deltaL,i,j,JDE0,JDE,JDE0tab,S,T,W,Y;if(year<1000){JDE0tab=JDE0tab1000;Y=year/1000}else{JDE0tab=JDE0tab2000;Y=(year-2000)/1000}
JDE0=JDE0tab[which][0]+(JDE0tab[which][1]*Y)+(JDE0tab[which][2]*Y*Y)+(JDE0tab[which][3]*Y*Y*Y)+(JDE0tab[which][4]*Y*Y*Y*Y);T=(JDE0-2451545.0)/36525;W=(35999.373*T)-2.47;deltaL=1+(0.0334*dcos(W))+(0.0007*dcos(2*W));S=0;for(i=j=0;i<24;i++){S+=EquinoxpTerms[j]*dcos(EquinoxpTerms[j+1]+(EquinoxpTerms[j+2]*T));j+=3}
JDE=JDE0+((S*0.00001)/deltaL);return JDE}
function sunpos(jd)
{var T,T2,L0,M,e,C,sunLong,sunAnomaly,sunR,Omega,Lambda,epsilon,epsilon0,Alpha,Delta,AlphaApp,DeltaApp;T=(jd-J2000)/JulianCentury;T2=T*T;L0=280.46646+(36000.76983*T)+(0.0003032*T2);L0=fixangle(L0);M=357.52911+(35999.05029*T)+(-0.0001537*T2);M=fixangle(M);e=0.016708634+(-0.000042037*T)+(-0.0000001267*T2);C=((1.914602+(-0.004817*T)+(-0.000014*T2))*dsin(M))+((0.019993-(0.000101*T))*dsin(2*M))+(0.000289*dsin(3*M));sunLong=L0+C;sunAnomaly=M+C;sunR=(1.000001018*(1-(e*e)))/(1+(e*dcos(sunAnomaly)));Omega=125.04-(1934.136*T);Lambda=sunLong+(-0.00569)+(-0.00478*dsin(Omega));epsilon0=obliqeq(jd);epsilon=epsilon0+(0.00256*dcos(Omega));Alpha=rtd(Math.atan2(dcos(epsilon0)*dsin(sunLong),dcos(sunLong)));Alpha=fixangle(Alpha);Delta=rtd(Math.asin(dsin(epsilon0)*dsin(sunLong)));AlphaApp=rtd(Math.atan2(dcos(epsilon)*dsin(Lambda),dcos(Lambda)));AlphaApp=fixangle(AlphaApp);DeltaApp=rtd(Math.asin(dsin(epsilon)*dsin(Lambda)));return new Array(L0,M,e,C,sunLong,sunAnomaly,sunR,Lambda,Alpha,Delta,AlphaApp,DeltaApp)}
function equationOfTime(jd)
{var alpha,deltaPsi,E,epsilon,L0,tau
tau=(jd-J2000)/JulianMillennium;L0=280.4664567+(360007.6982779*tau)+(0.03032028*tau*tau)+((tau*tau*tau)/49931)+(-((tau*tau*tau*tau)/15300))+(-((tau*tau*tau*tau*tau)/2000000));L0=fixangle(L0);alpha=sunpos(jd)[10];deltaPsi=nutation(jd)[0];epsilon=obliqeq(jd)+nutation(jd)[1];E=L0+(-0.0057183)+(-alpha)+(deltaPsi*dcos(epsilon));E=E-20.0*(Math.floor(E/20.0));E=E/(24*60);return E}
var J0000=1721424.5;var J1970=2440587.5;var JMJD=2400000.5;var J1900=2415020.5;var J1904=2416480.5;var NormLeap=new Array("Normal year","Leap year");function weekday_before(weekday,jd)
{return jd-jwday(jd-weekday)}
function search_weekday(weekday,jd,direction,offset)
{return weekday_before(weekday,jd+(direction*offset))}
function nearest_weekday(weekday,jd)
{return search_weekday(weekday,jd,1,3)}
function next_weekday(weekday,jd)
{return search_weekday(weekday,jd,1,7)}
function next_or_current_weekday(weekday,jd)
{return search_weekday(weekday,jd,1,6)}
function previous_weekday(weekday,jd)
{return search_weekday(weekday,jd,-1,1)}
function previous_or_current_weekday(weekday,jd)
{return search_weekday(weekday,jd,1,0)}
function TestSomething()
{}
function leap_gregorian(year)
{return((year%4)==0)&&(!(((year%100)==0)&&((year%400)!=0)))}
var GREGORIAN_EPOCH=1721425.5;function gregorian_to_jd(year,month,day)
{return(GREGORIAN_EPOCH-1)+(365*(year-1))+Math.floor((year-1)/4)+(-Math.floor((year-1)/100))+Math.floor((year-1)/400)+Math.floor((((367*month)-362)/12)+((month<=2)?0:(leap_gregorian(year)?-1:-2))+day)}
function jd_to_gregorian(jd){var wjd,depoch,quadricent,dqc,cent,dcent,quad,dquad,yindex,dyindex,year,yearday,leapadj;wjd=Math.floor(jd-0.5)+0.5;depoch=wjd-GREGORIAN_EPOCH;quadricent=Math.floor(depoch/146097);dqc=mod(depoch,146097);cent=Math.floor(dqc/36524);dcent=mod(dqc,36524);quad=Math.floor(dcent/1461);dquad=mod(dcent,1461);yindex=Math.floor(dquad/365);year=(quadricent*400)+(cent*100)+(quad*4)+yindex;if(!((cent==4)||(yindex==4))){year++}
yearday=wjd-gregorian_to_jd(year,1,1);leapadj=((wjd<gregorian_to_jd(year,3,1))?0:(leap_gregorian(year)?1:2));month=Math.floor((((yearday+leapadj)*12)+373)/367);day=(wjd-gregorian_to_jd(year,month,1))+1;return new Array(year,month,day)}
function n_weeks(weekday,jd,nthweek)
{var j=7*nthweek;if(nthweek>0){j+=previous_weekday(weekday,jd)}else{j+=next_weekday(weekday,jd)}
return j}
function iso_to_julian(year,week,day)
{return day+n_weeks(0,gregorian_to_jd(year-1,12,28),week)}
function jd_to_iso(jd)
{var year,week,day;year=jd_to_gregorian(jd-3)[0];if(jd>=iso_to_julian(year+1,1,1)){year++}
week=Math.floor((jd-iso_to_julian(year,1,1))/7)+1;day=jwday(jd);if(day==0){day=7}
return new Array(year,week,day)}
function iso_day_to_julian(year,day)
{return(day-1)+gregorian_to_jd(year,1,1)}
function jd_to_iso_day(jd)
{var year,day;year=jd_to_gregorian(jd)[0];day=Math.floor(jd-gregorian_to_jd(year,1,1))+1;return new Array(year,day)}
function pad(str,howlong,padwith){var s=str.toString();while(s.length<howlong){s=padwith+s}
return s}
var JULIAN_EPOCH=1721423.5;function leap_julian(year)
{return mod(year,4)==((year>0)?0:3)}
function julian_to_jd(year,month,day)
{if(year<1){year++}
if(month<=2){year--;month+=12}
return((Math.floor((365.25*(year+4716)))+Math.floor((30.6001*(month+1)))+day)-1524.5)}
function jd_to_julian(td){var z,a,alpha,b,c,d,e,year,month,day;td+=0.5;z=Math.floor(td);a=z;b=a+1524;c=Math.floor((b-122.1)/365.25);d=Math.floor(365.25*c);e=Math.floor((b-d)/30.6001);month=Math.floor((e<14)?(e-1):(e-13));year=Math.floor((month>2)?(c-4716):(c-4715));day=b-d-Math.floor(30.6001*e);if(year<1){year--}
return new Array(year,month,day)}
var HEBREW_EPOCH=347995.5;function hebrew_leap(year)
{return mod(((year*7)+1),19)<7}
function hebrew_year_months(year)
{return hebrew_leap(year)?13:12}
function hebrew_delay_1(year)
{var months,days,parts;months=Math.floor(((235*year)-234)/19);parts=12084+(13753*months);day=(months*29)+Math.floor(parts/25920);if(mod((3*(day+1)),7)<3){day++}
return day}
function hebrew_delay_2(year)
{var last,present,next;last=hebrew_delay_1(year-1);present=hebrew_delay_1(year);next=hebrew_delay_1(year+1);return((next-present)==356)?2:(((present-last)==382)?1:0)}
function hebrew_year_days(year)
{return hebrew_to_jd(year+1,7,1)-hebrew_to_jd(year,7,1)}
function hebrew_month_days(year,month)
{if(month==2||month==4||month==6||month==10||month==13){return 29}
if(month==12&&!hebrew_leap(year)){return 29}
if(month==8&&!(mod(hebrew_year_days(year),10)==5)){return 29}
if(month==9&&(mod(hebrew_year_days(year),10)==3)){return 29}
return 30}
function hebrew_to_jd(year,month,day)
{var jd,mon,months;months=hebrew_year_months(year);jd=HEBREW_EPOCH+hebrew_delay_1(year)+hebrew_delay_2(year)+day+1;if(month<7){for(mon=7;mon<=months;mon++){jd+=hebrew_month_days(year,mon)}
for(mon=1;mon<month;mon++){jd+=hebrew_month_days(year,mon)}}else{for(mon=7;mon<month;mon++){jd+=hebrew_month_days(year,mon)}}
return jd}
function jd_to_hebrew(jd)
{var year,month,day,i,count,first;jd=Math.floor(jd)+0.5;count=Math.floor(((jd-HEBREW_EPOCH)*98496.0)/35975351.0);year=count-1;for(i=count;jd>=hebrew_to_jd(i,7,1);i++){year++}
first=(jd<hebrew_to_jd(year,1,1))?7:1;month=first;for(i=first;jd>hebrew_to_jd(year,i,hebrew_month_days(year,i));i++){month++}
day=(jd-hebrew_to_jd(year,month,1))+1;return new Array(year,month,day)}
function equinoxe_a_paris(year)
{var equJED,equJD,equAPP,equParis,dtParis;equJED=equinox(year,2);equJD=equJED-(deltat(year)/(24*60*60));equAPP=equJD+equationOfTime(equJED);dtParis=(2+(20/60.0)+(15/(60*60.0)))/360;equParis=equAPP+dtParis;return equParis}
function paris_equinoxe_jd(year)
{var ep,epg;ep=equinoxe_a_paris(year);epg=Math.floor(ep-0.5)+0.5;return epg}
var FRENCH_REVOLUTIONARY_EPOCH=2375839.5;function annee_da_la_revolution(jd)
{var guess=jd_to_gregorian(jd)[0]-2,lasteq,nexteq,adr;lasteq=paris_equinoxe_jd(guess);while(lasteq>jd){guess--;lasteq=paris_equinoxe_jd(guess)}
nexteq=lasteq-1;while(!((lasteq<=jd)&&(jd<nexteq))){lasteq=nexteq;guess++;nexteq=paris_equinoxe_jd(guess)}
adr=Math.round((lasteq-FRENCH_REVOLUTIONARY_EPOCH)/TropicalYear)+1;return new Array(adr,lasteq)}
function jd_to_french_revolutionary(jd)
{var an,mois,decade,jour,adr,equinoxe;jd=Math.floor(jd)+0.5;adr=annee_da_la_revolution(jd);an=adr[0];equinoxe=adr[1];mois=Math.floor((jd-equinoxe)/30)+1;jour=(jd-equinoxe)%30;decade=Math.floor(jour/10)+1;jour=(jour%10)+1;return new Array(an,mois,decade,jour)}
function french_revolutionary_to_jd(an,mois,decade,jour)
{var adr,equinoxe,guess,jd;guess=FRENCH_REVOLUTIONARY_EPOCH+(TropicalYear*((an-1)-1));adr=new Array(an-1,0);while(adr[0]<an){adr=annee_da_la_revolution(guess);guess=adr[1]+(TropicalYear+2)}
equinoxe=adr[1];jd=equinoxe+(30*(mois-1))+(10*(decade-1))+(jour-1);return jd}
function leap_islamic(year)
{return(((year*11)+14)%30)<11}
var ISLAMIC_EPOCH=1948439.5;var ISLAMIC_WEEKDAYS=new Array("al-'ahad","al-'ithnayn","ath-thalatha'","al-'arb`a'","al-khamis","al-jum`a","as-sabt");function islamic_to_jd(year,month,day)
{return(day+Math.ceil(29.5*(month-1))+(year-1)*354+Math.floor((3+(11*year))/30)+ISLAMIC_EPOCH)-1}
function jd_to_islamic(jd)
{var year,month,day;jd=Math.floor(jd)+0.5;year=Math.floor(((30*(jd-ISLAMIC_EPOCH))+10646)/10631);month=Math.min(12,Math.ceil((jd-(29+islamic_to_jd(year,1,1)))/29.5)+1);day=(jd-islamic_to_jd(year,month,1))+1;return new Array(year,month,day)}
function leap_persian(year)
{return((((((year-((year>0)?474:473))%2820)+474)+38)*682)%2816)<682}
var PERSIAN_EPOCH=1948320.5;var PERSIAN_WEEKDAYS=new Array("Yekshanbeh","Doshanbeh","Seshhanbeh","Chaharshanbeh","Panjshanbeh","Jomeh","Shanbeh");function persian_to_jd(year,month,day)
{var epbase,epyear;epbase=year-((year>=0)?474:473);epyear=474+mod(epbase,2820);return day+((month<=7)?((month-1)*31):(((month-1)*30)+6))+Math.floor(((epyear*682)-110)/2816)+(epyear-1)*365+Math.floor(epbase/2820)*1029983+(PERSIAN_EPOCH-1)}
function jd_to_persian(jd)
{var year,month,day,depoch,cycle,cyear,ycycle,aux1,aux2,yday;jd=Math.floor(jd)+0.5;depoch=jd-persian_to_jd(475,1,1);cycle=Math.floor(depoch/1029983);cyear=mod(depoch,1029983);if(cyear==1029982){ycycle=2820}else{aux1=Math.floor(cyear/366);aux2=mod(cyear,366);ycycle=Math.floor(((2134*aux1)+(2816*aux2)+2815)/1028522)+aux1+1}
year=ycycle+(2820*cycle)+474;if(year<=0){year--}
yday=(jd-persian_to_jd(year,1,1))+1;month=(yday<=186)?Math.ceil(yday/31):Math.ceil((yday-6)/30);day=(jd-persian_to_jd(year,month,1))+1;return new Array(year,month,day)}
var MAYAN_COUNT_EPOCH=584282.5;function mayan_count_to_jd(baktun,katun,tun,uinal,kin)
{return MAYAN_COUNT_EPOCH+(baktun*144000)+(katun*7200)+(tun*360)+(uinal*20)+kin}
function jd_to_mayan_count(jd)
{var d,baktun,katun,tun,uinal,kin;jd=Math.floor(jd)+0.5;d=jd-MAYAN_COUNT_EPOCH;baktun=Math.floor(d/144000);d=mod(d,144000);katun=Math.floor(d/7200);d=mod(d,7200);tun=Math.floor(d/360);d=mod(d,360);uinal=Math.floor(d/20);kin=mod(d,20);return new Array(baktun,katun,tun,uinal,kin)}
var MAYAN_HAAB_MONTHS=new Array("Pop","Uo","Zip","Zotz","Tzec","Xul","Yaxkin","Mol","Chen","Yax","Zac","Ceh","Mac","Kankin","Muan","Pax","Kayab","Cumku","Uayeb");function jd_to_mayan_haab(jd)
{var lcount,day;jd=Math.floor(jd)+0.5;lcount=jd-MAYAN_COUNT_EPOCH;day=mod(lcount+8+((18-1)*20),365);return new Array(Math.floor(day/20)+1,mod(day,20))}
var MAYAN_TZOLKIN_MONTHS=new Array("Imix","Ik","Akbal","Kan","Chicchan","Cimi","Manik","Lamat","Muluc","Oc","Chuen","Eb","Ben","Ix","Men","Cib","Caban","Etznab","Cauac","Ahau");function jd_to_mayan_tzolkin(jd)
{var lcount;jd=Math.floor(jd)+0.5;lcount=jd-MAYAN_COUNT_EPOCH;return new Array(amod(lcount+20,20),amod(lcount+4,13))}
var BAHAI_EPOCH=2394646.5;var BAHAI_WEEKDAYS=new Array("Jamal","Kamal","Fidal","Idal","Istijlal","Istiqlal","Jalal");function bahai_to_jd(major,cycle,year,month,day)
{var gy;gy=(361*(major-1))+(19*(cycle-1))+(year-1)+jd_to_gregorian(BAHAI_EPOCH)[0];return gregorian_to_jd(gy,3,20)+(19*(month-1))+((month!=20)?0:(leap_gregorian(gy+1)?-14:-15))+day}
function jd_to_bahai(jd)
{var major,cycle,year,month,day,gy,bstarty,bys,days,bld;jd=Math.floor(jd)+0.5;gy=jd_to_gregorian(jd)[0];bstarty=jd_to_gregorian(BAHAI_EPOCH)[0];bys=gy-(bstarty+(((gregorian_to_jd(gy,1,1)<=jd)&&(jd<=gregorian_to_jd(gy,3,20)))?1:0));major=Math.floor(bys/361)+1;cycle=Math.floor(mod(bys,361)/19)+1;year=mod(bys,19)+1;days=jd-bahai_to_jd(major,cycle,year,1,1);bld=bahai_to_jd(major,cycle,year,20,1);month=(jd>=bld)?20:(Math.floor(days/19)+1);day=(jd+1)-bahai_to_jd(major,cycle,year,month,1);return new Array(major,cycle,year,month,day)}
var INDIAN_CIVIL_WEEKDAYS=new Array("ravivara","somavara","mangalavara","budhavara","brahaspativara","sukravara","sanivara");function indian_civil_to_jd(year,month,day)
{var Caitra,gyear,leap,start,jd,m;gyear=year+78;leap=leap_gregorian(gyear);start=gregorian_to_jd(gyear,3,leap?21:22);Caitra=leap?31:30;if(month==1){jd=start+(day-1)}else{jd=start+Caitra;m=month-2;m=Math.min(m,5);jd+=m*31;if(month>=8){m=month-7;jd+=m*30}
jd+=day-1}
return jd}
function jd_to_indian_civil(jd)
{var Caitra,Saka,greg,greg0,leap,start,year,yday,mday;Saka=79-1;start=80;jd=Math.floor(jd)+0.5;greg=jd_to_gregorian(jd);leap=leap_gregorian(greg[0]);year=greg[0]-Saka;greg0=gregorian_to_jd(greg[0],1,1);yday=jd-greg0;Caitra=leap?31:30;if(yday<start){year--;yday+=Caitra+(31*5)+(30*3)+10+start}
yday-=start;if(yday<Caitra){month=1;day=yday+1}else{mday=yday-Caitra;if(mday<(31*5)){month=Math.floor(mday/31)+2;day=(mday%31)+1}else{mday-=31*5;month=Math.floor(mday/30)+7;day=(mday%30)+1}}
return new Array(year,month,day)}