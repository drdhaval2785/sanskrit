<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="mystyle.css">
</link>
</meta>
</head>
<body>
<?php
 header('Content-type: text/html; charset=utf-8');
 
 /* This code is developed by Dr. Dhaval Patel (drdhaval2785@gmail.com) of www.sanskritworld.in and Ms. Sivakumari Katuri.
  * Layout assistance by Mr Marcis Gasuns.
  * Available under GNU licence.
  * Version 1.2 date 5/12/2013
  * The latest source code is available at https://github.com/drdhaval2785/sanskrit
  * For setup, copy and paste sandhi.html, sandhi.php, function.php, mystyle.css, slp-dev.php and dev-slp.php to your localhost and server and run sandhi.html.
  * sandhi.html is the frontend for the code.
  * function.php stores the frequently used functions in this code (The description on how to use the code is there in function.php.
  * slp-dev.php is for converting SLP1 data to Devanagari. dev-slp.php is for converting Devanagari data to SLP1.
  * Mystyle.css is stylesheet where you can change your preferences.
  */
 
// Including arrays and functions 
include "function.php";
include "slp-dev.php";
include "dev-slp.php";
// set execution time to an hour
ini_set('max_execution_time', 36000);
// set memory limit to 1000 MB
ini_set("memory_limit","1000M");
// Reading from the HTML input.
$first = $_GET["first"];
$second = $_GET['second'];
$tran = $_GET['trans'];
$pada = $_GET['pada'];
$sambuddhi = 0;
$nadi = 0;
$gender = "m";
$GI = 0;
//$GI = 1;
$Ap = 0;
$taddhita = 0;
$dhatu = 0;
$asaMyogapUrva = 0; // Not clear how to differentiate between iyaG and yaN
// Code for converting from IAST to SLP
$iast = array("a","ā","i","ī","u","ū","ṛ","ṝ","ḷ","ḹ","e","ai","o","au","ṃ","ḥ","kh","ch","ṭh","th","ph","gh","jh","ḍh","dh","bh","ṅ","ñ","ṇ","k","c","ṭ","t","p","g","j","ḍ","d","b","n","m","y","r","l","v","s","h","ś","ṣ",);
$slp = array("a","A","i","I","u","U","f","F","x","X","e","E", "o","O", "M","H","K", "C",  "W", "T", "P","G", "J",  "Q", "D","B", "N","Y","R","k","c","w","t","p","g","j","q","d","b","n","m","y","r","l","v","s","h","S","z",);
  if (preg_match('/[āĀīĪūŪṛṚṝṜḷḶḹḸṃṂḥḤṭṬḍḌṅṄñÑṇṆśŚṣṢV]/',$first) || preg_match('/[āĀīĪūŪṛṚṝṜḷḶḹḸṃṂḥḤṭṬḍḌṅṄñÑṇṆśŚṣṢV]/',$second))
{
    $first = str_replace($iast,$slp,$first);
    $second = str_replace($iast,$slp,$second);
}
if ($tran === "IAST")
{
     $first = str_replace($iast,$slp,$first);
    $second = str_replace($iast,$slp,$second);
 }
// Devanagari handling. This is innocuous. Therefore even running without the selection in dropdown menu. 
$first = json_encode($first);
$first = str_replace("\u200d","",$first);
$first = str_replace("\u200c","",$first);
$first = json_decode($first);
$second = json_encode($second);
$second = str_replace("\u200d","",$second);
$second = str_replace("\u200c","",$second);
$second = json_decode($second);
$first = convert1($first);
$second = convert1($second);

$fo = $first;
$so = $second; ;
// displaying the data back to the user
echo "<p class = no >You entered: ".convert($fo)." + ".convert($so)." <a href = sandhi.html>Go Back</a></p>";
echo "</br>";

/* preprocessing for the sup pratyayas. */
// Datara / Datama are pratyayas. Pending . apuri vaktavyam pending.

// Joining the two input words 
if ($second === "")
{
    $input = $input = ltrim(chop($first));
}
elseif ($first === "")
{
    $input = ltrim(chop($second));
}
else
{
$input = ltrim(chop($first."+".$second));    
}
 /* main coding part starts from here. Based on Siddhantakaumudi text. */
    
// Defining an array $text    
$text = array();
// Defining first member of the array as $input (combined words first and second)
$text[] = $input;

/* Defining pada and bham */
/* suDanapuMsakasya (1.1.43) */ // implied in the array.
/* svAdiSvasarvanAmasthAne (1.4.17) */
if ($gender !== "n" && !in_array($so,$sarvanamasthana) && in_array($so,$sup))
{
    $pada = "pada";
}
/* bahuguNavatuDati saGkhyA (1.1.28) */
/* Dati ca (1.1.25) */
if (in_array($fo,array("bahu","guRa")))
{
    $sankhya = 1;
    echo "<p class = sa >By bahuguNavatuDati saGkhyA (1.1.28) :</p>";
    echo "<p class = sa >बहुगुणवतुडति सङ्ख्या (१.१.२८) :</p>";
    display(0);
}
else 
{
    $sankhya = 0;
}
if (in_array($fo,array("kati")))
{
    $sankhya = 1;
    $shat = 1;
    echo "<p class = hn >By bahuguNavatuDati saGkhyA (1.1.28) and Dati ca (1.1.25) :</p>";
    echo "<p class = hn >बहुगुणवतुडति सङ्ख्या (१.१.२८) तथा डति च (१.१.२५) :</p>";
    display(0);
}
else 
{
    $sankhya = 0;
    $shat = 0;
}
/* yU stryAkhyau nadI (1.4.3) and prathamaliGgagrahaNaJca (vA 1036) */
// the vArtika is not coded perfectly. Also stryAkhyo is pending.
if ( $gender==="of" && (( arr($text,'/[IU][+]/')) || in_array($fo,array("bahuSreyasI","aticamU"))))
{
    if (in_array($fo,array("bahuSreyasI","aticamU")))
    {
    echo "<p class = hn >By yU stryAkhyau nadI (1.4.3) and prathamaliGgagrahaNaJca (vA 1036) :</p>";
    echo "<p class = hn >यू स्त्र्याख्यौ नदी (१.४.३) तथा प्रथमलिङ्गग्रहणञ्च (वा १०३६) :</p>";
    display(0);
    }
    else
    {
    echo "<p class = hn >By yU stryAkhyau nadI (1.4.3) :</p>";
    echo "<p class = hn >यू स्त्र्याख्यौ नदी (१.४.३) :</p>";
    display(0);
    }
    $nadi = 1;
}
else
{
    $nadi = 0;
}
/* pratyayasya lukzlulupaH (1.1.61) */
/* SaDbhyo luk (7.1.22) */
if ( $shat === 1 && in_array($so,array("jas","Sas")))
{
    $text = two(array("+"),array("jas","Sas"),array("+"),array("",""),0);
    echo "<p class = sa >By SaDbhyo luk (7.1.22) :</p>";
    echo "<p class = sa >षड्भ्यो लुक्‌ (७.१.२२) :</p>";
    display(0); $luk = 1;
} else { $luk = 0; }
/* pratyayalope pratyayalakSaNam (1.1.62) and na lumatAGgasya (1.1.63) */
if ($luk === 1)
{
    echo "<p class = hn >pratyayalope pratyayalakSaNam (1.1.62) is overridden by na lumatAGgasya (1.1.63) :</p>";
    echo "<p class = hn >प्रत्ययलोपे प्रत्ययलक्षणम्‌ (१.१.६२) का न लुमताङ्गस्य (१.१.६३) से बाध हुआ है । :</p>";
    display(0);
}
/* yaci bham (1.4.14) and A kaDArAdekA saJjJA (1.4.1) */
// Not coded perfectly. Only for sup pratyayas.
if ($gender !== "n" && in_array($so,$yacibham) && in_array($so,$sup))
{
    $pada = "pratyaya"; 
    $bham = 1;
    echo "<p class = sa >By yaci bham (1.4.14) and A kaDArAdekA saJjJA (1.4.1) :</p>";
    echo "<p class = sa >यचि भम्‌ (१.४.१४) तथा आ कडारादेका सञ्ज्ञा (१.४.१) :</p>";
    display(0);
} else {$bham = 0; }
/* jarAyA jarasanyatarasyAm (7.2.101) */
if (arr($text,'/(jara)[+]/') && $pada=== "pratyaya"  && in_array($so,$acsup) )
    {
    $text = one(array("jara"),array("jaras"),1);
    echo "<p class = sa >By jarAyA jarasanyatarasyAm (7.2.101) :</p>";
    echo "<p class = sa >जराया जरसन्यतरस्याम्‌ (७.२.१०१) :</p>";
    display(0);
    }
/* paddannomAshRnnizasanyUSandoSanyakaJChakannudannAsaJChasprabhRtiSu (6.1.63) */
// The random examples given under vibhASA GizyoH on page 147 are pending. Will do them after understanding fully. 
$paddanno = array("pAda","danta","nAdikA","mAsa","hfdaya","niSA","asfj","yUza","doz","yakft","Sakft","udaka","Asya");
$paddanno1 = array("pad","dat","nas","mAs","hfd","niS","asan","yUzan","dozan","yakan","Sakan","udan","Asan");
if (sub($paddanno,array("+"),$zasadi,0) && in_array($so,$zasadi))
{
    $text = two($paddanno,$zasadi,$paddanno1,$zasadi,1);
    echo "<p class = sa >By paddannomAshRnnizasanyUSandoSanyakaJChakannudannAsaJChasprabhRtiSu (6.1.63) :</p>";
    echo "<p class = sa >पद्दन्नोमाशृन्निशसन्यूषन्दोषन्यकञ्छकन्नुदन्नासञ्छस्प्रभृतिषु (६.१.६३) :</p>";
    display(0);
}
/* saGkhyAvisAyapUrvasyAhnasyAhannanyatarasyAM Gau (6.3.110) */
$sankhyahan = array("ekAhna","dvyahna","tryahna","caturahna","paJcAhna","zaDAhna","saptAhna","azwAhna","navAhna","daSAhna","vyahna","sAyAhna");
if (sub($sankhyahan,array("+"),array("Ni"),0) && $so==="Ni")
{
    $text = two(array("hna"),array("+"),array("han"),array("+"),1);
    echo "<p class = sa >By saGkhyAvisAyapUrvasyAhnasyAhannanyatarasyAM Gau (6.3.110) :</p>";
    echo "<p class = sa >सङ्ख्याविसायपूर्वस्याह्नस्याहन्नन्यतरस्याम्‌ (६.३.११०) :</p>";
    display(0);
}
/* sakhyurasambuddhau (7.1.92) */
if (sub(array("saKi","saKI"),$sarvanamasthana,blank(0),0) && $sambuddhi===0)
{   $Nidvat = 1;
    echo "<p class = sa >By sakhyurasambuddhau (7.1.92) :</p>";
    echo "<p class = sa >सख्युरसम्बुद्धौ (७.१.९२) :</p>";
    display(0);
} else {$Nidvat=0; }
/* anaG sau (7.1.93) and Gicca (1.1.53) */
if (sub(array("saKi","saKI"),array("+"),array("su!"),0) && $so==="su!" && $sambuddhi===0)
{
    $text = two(array("saKi","saKI"),array("+"),array("saKan","saKan"),array("+"),0);
    echo "<p class = sa >By anaG sau (7.1.93) and Gicca (1.1.53)  :</p>";
    echo "<p class = sa >अनङ्‌ सौ (७.१.९३) तथा ङिच्च (१.१.५३) :</p>";
    display(0);
}
/* alo'ntyAtpUrva upadhA (1.1.65) */ 
// saJjJA sUtra. Nothing to code here.
/* sarvanAmasthAne cAsambuddhau (6.4.8) */
$acdir = array("A","A","I","I","U","U","F","F","F","F","e","o","E","O",);
if (arr($text,'/['.flat($ac).'][n][+]/') && in_array($so,$sarvanamasthana) && $sambuddhi===0)
{
    $text = two($ac,array("n+"),$acdir,array("n+"),0);
    echo "<p class = sa >By sarvanAmasthAne cAsambuddhau (6.4.8) :</p>";
    echo "<p class = sa >सर्वनामस्थाने चासम्बुद्धौ (६.४.८) :</p>";
    display(0);
}
/* lomno'patyeSu bahuSvakAro vaktavyaH (vA 2560) */
// Pending, because it is for taddhita derivation. Right now made a patch.
$bahusup = array("jas","Sas","Bis","Byas","Am","sup");
if (sub(array("Oqulomi"),array("+"),$bahusup,0) && in_array($so,$bahusup))
{
    $text = two(array("Oqulomi"),$bahusup,array("Oquloma"),$bahusup,0);
    echo "<p class = sa >By lomno'patyeSu bahuSvakAro vaktavyaH (vA 2560) :</p>";
    echo "<p class = sa >लोम्नोऽपत्येषु बहुष्वकारो वक्तव्यः (वा २५६०) :</p>";
    display(0);
}
/* tRjvatkroSTuH (7.1.95), vibhASA tRtIyAdiSvaci (7.1.97) and numaciratRjvadbhAvebhyo nuT pUrvavipratiSedhena (vA 4374) */
if ((sub(array("krozwu"),array("+"),$sarvanamasthana,0)||sub(array("krozwu"),array("+"),$tRtIyAdiSvaci,0) ) && $pada==="pratyaya" && $sambuddhi===0)
{
    if (sub(array("krozwu"),array("+"),$sarvanamasthana,0))
    {
    $text = two(array("krozwu"),array("+"),array("krozwf"),array("+"),0);
    echo "<p class = sa >By tRjvatkroSTuH (7.1.95) :</p>";
    echo "<p class = sa >तृज्वत्क्रोष्टुः (७.१.९५) :</p>";
    display(0);        
    }
    if (sub(array("krozwu"),array("+"),$tRtIyAdiSvaci,0) && $so!=="Am")
    {
    $text = two(array("krozwu"),array("+"),array("krozwf"),array("+"),1);
    echo "<p class = sa >vibhASA tRtIyAdiSvaci (7.1.97) :</p>";
    echo "<p class = sa >विभाषा तृतीयादिष्वचि (७.१.९७) :</p>";
    display(0);        
    }
    $tRcvat=1;
} 
else
{
    $tRcvat=0;
}
if (sub(array("krozwu"),array("+"),array("Am"),0) && $so==="Am")
{ 
    $text = two(array("krozwu"),array("+Am"),array("krozwu"),array("+nAm"),0);
    echo "<p class = sa >numaciratRjvadbhAvebhyo nuT pUrvavipratiSedhena (vA 4374) :</p>";
    echo "<p class = hn >This vArttika mandates a 'nuT' Agama. :</p>";
    echo "<p class = sa >नुमचिरतृज्वद्भावेभ्यो नुट्‌ पूर्वविप्रतिषेधेन (वा ४३७४) :</p>";
    echo "<p class = hn >यह वार्तिक नुट्‌ आगम का विधान करता है ।</p>";
    display(0);  $numacira=1;       
} else { $numacira = 0;}
/* RduzanaspurudaMso&nehasAM ca (7.1.94) */
if ( (arr($text,'/[fx][+]/')|| $fo==="uSanas"|| $fo==="purudaMsas" || $fo==="anehas" ) &&  $so==="su!" && $pada==="pratyaya" && $sambuddhi===0)
{
    $text = two(array("f","x","uSanas","purudaMsas","anehas"),array("+"),array("an","an","uSanan","purudaMsan","anehan"),array("+"),0);
    echo "<p class = sa >By RduzanaspurudaMso&nehasAM ca (7.1.94) :</p>";
    echo "<p class = sa >ऋदुशनस्पुरुदंसोऽनेहसां च (७.१.९४) :</p>";
    display(0); $Rduza=1; 
} else { $Rduza=0; }
/* Rto GitsarvanAmasthAnayoH (7.3.110) */
if (arr($text,'/[fx][+]/') && ( in_array($so,$sarvanamasthana) || $so==="Ni") && $pada==="pratyaya" && $sambuddhi===0)
{
    $text = two(array("f","x"),array("+"),array("ar","al"),array("+"),0);
    echo "<p class = sa >By Rto GisarvanAmasthAnayoH (7.3.110) and uraNraparaH (1.1.51) :</p>";
    echo "<p class = sa >ऋतो ङिसर्वनामस्थानयोः (७.३.११०) तथा उरण्रपरः (१.१.५१) :</p>";
    display(0);
}
/* aptRntRcsvasRnaptRneSTRtvaSTRkSattRhotRpotRprazAstRRNAm (6.4.11) */
// Not coded perfectly for tRn and tRc. naptrAdigrahaNaM vyutpattipakSe niyamArtham is pending.
$svasR = array("svasf","naptf","nezwf","tvazwf","kzattf","hotf","potf","praSAstf");
$excludesvasR = array("pitf","BrAtf","jAmAtf","mantf","hantf");
if (((sub(array("tar+","war+"),blank(0),blank(0),0) && !in_array($fo,$excludesvasR) )|| ($tRcvat===1 )||in_array($fo,$svasR) || $Rduza===1) && in_array($so,$sarvanamasthana) && $pada==="pratyaya" && $sambuddhi===0)
{
    $text = three($ac,array("r","n"),array("+"),dirgha($ac),array("r","n"),array("+"),0);
    echo "<p class = sa >By aptRntRcsvasRnaptRneSTRtvaSTRkSattRhotRpotRprazAstRRNAm (6.4.11) :</p>";
    echo "<p class = sa >अप्तृन्तृच्स्वसृनप्तृनेष्टृत्वष्टृक्षत्तृहोतृपोतृप्रशास्तॄणाम्‌ (६.४.११) :</p>";
    display(0);
}
/* Ato dhAtoH (6.4.140) */
// Right now we have presumed that all are AkArAnta dhAtus. Abanta etc will have to be dealt with later on.
$haha = array("hAhA");
if ($bham === 1 && arr($text,'/[A][+]/') && !in_array($fo,$haha))
{
    $text = two(array("A"),array("+"),array(""),array("+"),0);
    echo "<p class = sa >By Ato dhAtoH (6.4.140) :</p>";
    echo "<p class = sa >आतो धातोः (६.४.१४०) :</p>";
    display(0);
}
/* bhasya (6.4.129) and allopo'naH (6.4.134) and vibhASA GizyoH (6.4.236) */
if ($bham === 1 && arr($text,'/[a][n][+]/'))
{
    if ($so === "Ni")
    {
    $text = one(array("an+"),array("n+"),1);
    echo "<p class = sa >By bhasya (6.4.129), allopo'naH (6.4.134) and vibhASA GizyoH (6.4.236) :</p>";
    echo "<p class = sa >भस्य (६.४.१२९), अल्लोपोऽनः (६.४.१३४) तथा विभाषा ङिश्योः (६.४.२३६) :</p>";
    display(0);    
    }
    else
    {
    $text = one(array("an+"),array("n+"),0);
    echo "<p class = sa >By bhasya (6.4.129) and allopo'naH (6.4.134) :</p>";
    echo "<p class = sa >भस्य (६.४.१२९) तथा अल्लोपोऽनः (६.४.१३४) :</p>";
    display(0);    
    }
}
/* jasi ca (7.3.109) */
if (arr($text,'/[aiufx][+][j]/') && $so==="jas")
{
    $text = two(array("a","i","u","f","x"),array("+"),array("a","e","o","ar","al"),array("+"),0);
    echo "<p class = sa >By jasi ca (7.3.109) :</p>";
    echo "<p class = sa >जसि च (७.३.१०९) :</p>";
    display(0);
}
/* trestrayaH (7.1.53) */
if (arr($text,'/[t][r][i][+][A][m]$/') && $so==="Am" && $fo==="tri")
{
    $text = two(array("tri"),array("Am"),array("traya"),array("Am"),0);
    echo "<p class = sa >By trestrayaH (7.1.53) :</p>";
    echo "<p class = sa >त्रेस्त्रयः (७.१.५३) :</p>";
    display(0);
}
if (arr($text,'/[t][r][i][+][A][m]$/') && $so==="Am" && $fo!=="tri")
{
    $text = two(array("tri"),array("Am"),array("traya"),array("Am"),1);
    echo "<p class = sa >By trestrayaH (7.1.53) and 'gauNatve tu neti kecit' :</p>";
    echo "<p class = sa >त्रेस्त्रयः (७.१.५३) तथा 'गौणत्वे तु नेति केचित्‌' :</p>";
    display(0);
}
/* tyadAdInAmaH (7.2.102) */
// Not coded perfectly. Only for dvi.
// It is not possible to decide the prAdhAnya or gauNatva because it depends on speaker's choice.
$tyadadi = array("dvi",);
$tyadadinamah = array("dva");
if (sub($tyadadi,array("+"),$sup,0) && in_array($fo,$tyadadi))
{
    $text = two($tyadadi,$sup,$tyadadinamah,$sup,0);
    echo "<p class = sa >By tyadAdInAmaH (7.2.102) :</p>";
    echo "<p class = sa >त्यदादीनामः (७.२.१०२) :</p>";
    display(0);
}
/* goto Nit (7.1.90) and oto Niditi vAcyam (vA 5035) */
if (sub(array("o+"),$sarvanamasthana,blank(0),0))
{   $Nidvat1 = 1;
    if (sub(array("go+"),$sarvanamasthana,blank(0),0))
    {
    echo "<p class = sa >By goto Nit (7.1.90) :</p>";
    echo "<p class = sa >गोतो णित्‌ (७.१.९०) :</p>";        
    }
    else
    {
    echo "<p class = sa >By oto Niditi vAcyam (vA 5035) :</p>";
    echo "<p class = sa >ओतो णिदिति वाच्यम्‌ (वा ५०३५) :</p>";
    }
    display(0);
} else {$Nidvat1 =0; }

/* RRta iddhAtoH (7.1.100) */
$kRR = array("kF","tF","gF");
if (arr($text,'/[ktg][F][+]/'))
{
    $dhatu = 1;
}
if (arr($text,'/[ktg][F][+]/'))
{
    $text = two(array("F"),array("+"),array("ir"),array("+"),1);
    echo "<p class = sa >By RRta iddhAtoH (7.1.100) :</p>";
    echo "<p class = sa >ॠत इद्धातोः (७.१.१००) :</p>";
    display(0);    
}
if ( $dhatu === 1 && sub(array("F"),array("+"),blank(0),0) && !arr($text,'/[ktg][F][+]/') )
{
    $text = two(array("F"),array("+"),array("ir"),array("+"),0);
    echo "<p class = sa >By RRta iddhAtoH (7.1.100) :</p>";
    echo "<p class = sa >ॠत इद्धातोः (७.१.१००) :</p>";
    display(0);
}
/* rAyo hali (7.2.85) */
if (sub(array("rE"),array("+"),$hlsup,0) && in_array($so,$hlsup))
{
    $text = two(array("rE"),array("+"),array("rA"),array("+"),0);
    echo "<p class = sa >By rAyo hali (7.2.85) :</p>";
    echo "<p class = sa >रायो हलि (७.२.८५) :</p>";
    display(0);
}
/* hrasvasya guNaH (7.3.108) */
if (arr($text,'/[aiufx][+][s]/') && $so==="su!" && $sambuddhi===1)
{
    $text = two(array("a","i","u","f","x"),array("+"),array("a","e","o","ar","al"),array("+"),0);
    echo "<p class = sa >By hrasvasya guNaH (7.3.108) :</p>";
    echo "<p class = sa >ह्रस्वस्य गुणः (७.३.१०८) :</p>";
    display(0);
}
/* ambArthanadyorhrasvaH (7.3.103) */
if ($sambuddhi===1 &&  ($nadi===1 || in_array($fo,array("ambA"))) && $so==="su!")
{
    $text = two(array("A","I","U"),array("+"),array("a","i","u"),array("+"),0);
    echo "<p class = sa >By ambArthanadyorhrasvaH (7.3.103) :</p>";
    echo "<p class = sa >अम्बार्थनद्योर्ह्रस्वः :</p>";
    display(0);
}
/* zeSo ghyasakhi (1.4.7) */
if ($nadi===0 && arr($text,'/[iu][+]/') && $fo!=="saKi" && !arr($text,'/[iu][+]$/'))
{
    if (arr(array($fo),'/[p][a][t][i]$/') && $fo==='pati')
    {
        $ghi = 0;
        echo "<p class = hn >By patiH samAsa eva (1.4.8), the ghi saJjJA is not ascribed. </p>";
        echo "<p class = hn >पतिः समास एव (१.४.८) से घिसञ्ज्ञा नहीं है । </p>";
        display(0);        
    }
    else
    {
    $ghi = 1;
    echo "<p class = hn >By zeSo ghyasakhi (1.4.7) :</p>";
    echo "<p class = hn >शेषो घ्यसखि (१.४.७) :</p>";
    display(0);        
    }
}
else
{
    $ghi = 0;
}
/* aut (7.3.118) */
if (arr($text,'/[iu][+][N][i]$/') && $so==="Ni" && $pada==="pratyaya")
{
    $text = two(array("i","u",),array("Ni"),array("i","u"),array("O"),0);
    echo "<p class = sa >By aut (7.3.118) :</p>";
    echo "<p class = sa >औत्‌ (७.३.११८) :</p>";
    display(0);
}
/* ANnadyAH (7.3.112) */
// The method for finding Git is coarse. needs fine tuning.
if ($nadi===1 && arr($text,'/[+][N]/') && in_array($so,array("Ne","Nasi!","Nas","Ni")))
{
    $text = two(array("+"),array("N"),array("+"),array("A+N"),0);
    echo "<p class = sa >By ANnadyAH (7.3.112) :</p>";
    echo "<p class = sa >आण्नद्याः (७.३.११२) :</p>";
    display(0); $ANnadyAH =1;
} else {$ANnadyAH = 0; } 
// atisakhi is pending to code. page 158. Understand and then code.
/* AGo nA'striyAm (7.3.120) */ 
if ($ghi===1 && $so==="wA" && $gender !== "f")
{
    $text = two(array("i+","u+"),array("wA"),array("i+","u+"),array("nA"),0);
    echo "<p class = sa >By AGo nA'striyAm (7.3.120) :</p>";
    echo "<p class = sa >आङो नाऽस्त्रियाम्‌ (७.३.१२०) :</p>";
    display(0);
}
/* acca gheH (7.3.119) */ 
if ($ghi===1 && in_array($so,array("Ni")))
{
    $text = two(array("i","u"),array("O"),array("a","a"),array("O"),0);
    echo "<p class = sa >By acca gheH (7.3.119) :</p>";
    echo "<p class = sa >अच्च घेः (७.३.११९) :</p>";
    display(0);
}
/* gherGiti (7.3.111) */
if ($ghi===1 && arr($text,'/[iu][+]/') && in_array($so,array("Ne","Nasi!","Nas","Ni")))
{
    $text = two(array("i","u"),array("Ne","Nasi!","Nas","Ni"),array("e","o"),array("Ne","Nasi!","Nas","Ni"),0);
    echo "<p class = sa >By gherGiti (7.3.111) :</p>";
    echo "<p class = sa >घेर्ङिति (७.३.१११) :</p>";
    display(0);
}
/* jasaH shI (7.1.17) */
if (arr($text,'/[a][+]/') && $pada=== "pratyaya" && $so === "jas" && in_array($fo,$sarvanama))
    {
    $text = last(array("jas"),array("SI"),0);
    echo "<p class = sa >By jasaH shI (7.1.17) :</p>";
    echo "<p class = sa >जसः शी (७.१.१७) :</p>";
    display(0);
    $sarva2 =1;
} else { $sarva2 = 0; }
/* pUrvaparAvaradakSiNottarAparAdharANi vyavasthAyAmasaJjJAyAm (1.1.34) */
if ($so === "jas" && in_array($fo,array("pUrva","para","avara","dakziRa","uttara","apara","aDara",)))
{
    $text = last(array("SI"),array("jas"),1);
    echo "<p class = sa >By pUrvaparAvaradakSiNottarAparAdharANi vyavasthAyAmasaJjJAyAm (1.1.34) :</p>";
    echo "<p class = sa >पूर्वपरावरदक्षिणोत्तरापराधराणि व्यवस्थायामसंज्ञायाम्‌ (७.१.९) :</p>";
    display(0); $purva=1;
} else {$purva=0;}
/* svamajJAtidhanAkhyAyAm (1.1.35) */
if ($so === "jas" && in_array($fo,array("sva",)))
{
    $text = last(array("SI"),array("jas"),1);
    echo "<p class = sa >By svamajJAtidhanAkhyAyAm (1.1.35) :</p>";
    echo "<p class = sa >स्वमज्ञातिधनाख्यायाम्‌ (१.१.३५) :</p>";
    display(0); $sva=1;
} else {$sva=0;}
/* antaraM bahiryogopasaMvyAnayoH (1.1.36) */
if ($so === "jas" && in_array($fo,array("antara",)))
{
    $text = last(array("SI"),array("jas"),1);
    echo "<p class = sa >By antaraM bahiryogopasaMvyAnayoH (1.1.36) :</p>";
    echo "<p class = sa >अन्तरं बहिर्योगोपसंव्यानयोः (१.१.३६) :</p>";
    display(0); $sva=1;
} else {$sva=0;}

/* GerAmnadyAmnIbhyaH (7.3.116) */
if (($nadi===1||$Ap===1||arr($text,'/[n][I][+]/')) && $pada=== "pratyaya" && $so==="Ni")
{
    $text = two(array("+"),array("Ni"),array("+"),array("Am"),0);
    echo "<p class = sa >By GerAmnadyAmnIbhyaH (7.3.116) :</p>";
    echo "<p class = sa >ङेराम्नद्याम्नीभ्यः (७.३.११६) :</p>";
    display(0);
}
/* GasiGyoH smAtsminau (7.1.15) */ 
if (preg_match('/[a]$/',$fo) && $pada=== "pratyaya" && in_array($so,array("Nasi!","Ni")) && in_array($fo,$sarvanama))
{
    $text = last(array("Ni","Nasi!"),array("smin","smAt"),0);
    echo "<p class = sa >By GasiGyoH smAtsminau (7.1.15) :</p>";
    echo "<p class = sa >ङसिङ्योः स्मात्स्मिनौ (७.१.१५) :</p>";
    display(0);
    $sarva1 =1;
} else { $sarva1 = 0; }
/* vibhASAprakaraNe tIyasya GitsUpasaMkhyAnam (vA 242) */ 
if (preg_match('/[a]$/',$fo) && $pada=== "pratyaya" && in_array($so,array("Ne","Ni","Nasi!")) && ($fo==="dvitIya"||$fo==="tftIya"))
{
    $text = last(array("Ne","Ni","Nasi!"),array("smE","smin","smAt"),1);
    echo "<p class = sa >By vibhASAprakaraNe tIyasya GitsUpasaMkhyAnam (vA 242) :</p>";
    echo "<p class = sa >विभाषाप्रकरणे तीयस्य ङित्सूपसङ्ख्यानम्‌ (वा २४२) :</p>";
    display(0);
    $sarva1 =1;
} else { $sarva1 = 0; }
/* pUrvAdibhyo navabhyo vA (7.1.16) */ 
if (in_array($fo,array("pUrva","para","avara","dakziRa","uttara","apara","aDara","sva","antara")) && $pada=== "pratyaya" && in_array($so,array("Nasi!","Ni")))
{
    $text = last(array("smin","smAt"),array("Ni","Nasi!"),1);
    echo "<p class = sa >By pUrvAdibhyo navabhyo vA (7.1.16) :</p>";
    echo "<p class = sa >पूर्वादिभ्यो नवभ्यो वा (७.१.१६) :</p>";
    display(0);
    $sarva1 =1;
} elseif ($sarva1 ===1) 
    { $sarva1 = 1; }
    else
    {
        $sarva1 = 0;
    }
/* vibhASA jasi */
/* na bahuvrIhau */
/* tRtIyAsamAse */
/* dvandve ca */
/* prathamacaramatayAlpArdhakatipayanemAzca (1.1.33) */
if ($so === "jas" && in_array($fo,array("praTama","carama","alpa","arDa","katipaya","nema")))
{
    $text = last(array("jas"),array("SI"),1);
    echo "<p class = sa >By prathamacaramatayAlpArdhakatipayanemAzca (1.1.33) :</p>";
    echo "<p class = sa >प्रथमचरमतयाल्पार्धकतिपयनेमाश्च (१.१.३३) :</p>";
    display(0); $prath=1;
} else {$prath=0;}    
if ($so === "jas" && arr(array($fo),'/(taya)$/'))
{
    $text = last(array("jas"),array("SI"),1);
    echo "<p class = sa >By prathamacaramatayAlpArdhakatipayanemAzca (1.1.33) :</p>";
    echo "<p class = sa >प्रथमचरमतयाल्पार्धकतिपयनेमाश्च (१.१.३३) :</p>";
    display(0); $taya=1;
} else {$taya=0;}    
/* TAGasiGasAminAtsyAH (7.1.12) */
if (preg_match('/[a]$/',$fo) && in_array($so,array("wA","Nas")) && arr($text,'/[a][+]/'))
{
    $text = last(array("wA","Nas"),array("ina","sya"),0);
print_r($text);    $text = two(array("jaras"),array("ina","sya"),array("jaras"),array("wA","Nas"),0);
    echo "<p class = sa >By TAGasiGasAminAtsyAH (7.1.12) :</p>";
    echo "<p class = sa >टाङसिङसामिनात्स्याः (७.१.१२) :</p>";
    display(0);
    $wa = 1;
} else { $wa =0; }
if (preg_match('/[a]$/',$fo) && in_array($so,array("Nasi!")))
{
    $text = last(array("Nasi!"),array("At"),0);
    $text = two(array("jaras"),array("At"),array("jaras"),array("Nasi!"),0);
    echo "<p class = sa >By TAGasiGasAminAtsyAH (7.1.12) :</p>";
    echo "<p class = sa >टाङसिङसामिनात्स्याः (७.१.१२) :</p>";
    display(0);
    $wa1 = 1;
} else { $wa1 =0; }
/* upadeze'janunAsika it (1.3.2)*/ // Temporary patch. Not coded perfectly.
if (arr($text,'/['.flat($ac).'][!]/') && $pada=== "pratyaya" && $wa === 0  )
{
    $text = two($ac,array("!"),blank(count($ac)),array(""),0);
    echo "<p class = sa >By upadeze'janunAsika it (1.3.2) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >उपदेशेऽजनुनासिक इत्‌ (१.३.२) तथा तस्य लोपः (१.३.९) :</p>";
    display(0);    
}
/* AdirGiTuDavaH (1.3.5) */
if ((substr($first,0,2) === "Yi" || substr($first,0,2) === "wu" || substr($first,0,2) === "qu") && $pada=== "pratyaya")
{
    $text = first(array("Yi","wu","qu"),array("","",""),0);
    echo "<p class = sa >By AdirGiTuDavaH (1.3.5) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >आदिर्ञिटुडवः (१.३.५) तथा तस्य लोपः (१.३.९) :</p>";
    display(0);
}
/* cuTU (1.3.7) */
if (arr($text,'/[+][cCjJYwWqQR]/') && $pada=== "pratyaya" && $wa1 === 0 && ($sarva2 ===0 || $purva=1))
{
    $text = last(array("jas","wA"),array("as","A"),0);
    echo "<p class = sa >By cuTU (1.3.7) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >चुटू (१.३.७) तथा तस्य लोपः (१.३.९) :</p>";
    display(0);
}
/* SaH pratyayasya (1.3.6) */
if (arr($text,'/[+][z]/') && $pada=== "pratyaya")
{
    $second = substr($second,1);
    echo "<p class = sa >By SaH pratyayasya (1.3.6) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >षः प्रत्ययस्य (१.३.६) तथा तस्य लोपः (१.३.९) :</p>";
   echo convert($first).convert($second)."</br>";
}
/* sarvanAmnaH smai (7.1.14) */ 
if (arr($text,'/[a][+]/') && $pada=== "pratyaya" && $so === "Ne" && in_array($fo,$sarvanama))
{
    $text = last(array("Ne"),array("smE"),0);
    echo "<p class = sa >By sarvanAmnaH smai (7.1.14) :</p>";
    echo "<p class = sa >सर्वनाम्नः स्मै (७.१.१४) :</p>";
    display(0); $sarva =1;
} else { $sarva = 0; }
/* GeryaH (7.1.13) */
if (arr($text,'/[a][+]/') && $pada=== "pratyaya" && $so === "Ne" && $sarva === 0)
{
    $text = last(array("Ne"),array("ya"),0);
    $text = two(array("jaras"),array("ya"),array("jaras"),array("Ne"),0);
    echo "<p class = sa >By GeryaH (7.1.13) :</p>";
    echo "<p class = sa >ङेर्यः (७.१.१३) :</p>";
    display(0); $Ne=1;
} else { $Ne = 0; }
/* lazakvataddhite (1.3.8) */
if (((arr($text,'/[+][lSkKgGN]/'))||$sarva2===1||$purva===1) && in_array($so,$sup) && $taddhita === 0  && $sarva === 0 )
{
    $text = last(array("Sas","Ni","SI","Nas","Ne"),array("as","i","I","as","e"),0);
    echo "<p class = sa >By lazakvataddhite (1.3.8) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >लशक्वतद्धिते (१.३.८) तथा तस्य लोपः (१.३.९) :</p>";
    display(0);
}
/* na vibhaktau tusmAH (1.3.4) */
if (arr($text,'/[+][tTdDnsm]$/') && $pada=== "pratyaya" && in_array($so,$sup) && $wa === 0 && $wa1 === 0)
{
    echo "<p class = sa >By na vibhaktau tusmAH (1.3.4)  :</p>";
    echo "<p class = sa >न विभक्तौ तुस्माः (१.३.४) :</p>";
    display(0);
}
/* halantyam (1.3.3) and tasya lopaH */
if ((arr($text,'/['.flat($hl).']$/') && $pada=== "pratyaya" && !in_array($so,$sup)) || (in_array($so,$sup) && !preg_match('/[tTdDnsm]$/',$so) && preg_match('/['.flat($hl).']$/',$so)))
{
    $text = last(prat('hl'),blank(count(prat('hl'))),0);
    echo "<p class = sa >By halantyam (1.3.3) and tasya lopaH (1.3.9) :</p>";
    echo "<p class = sa >हलन्त्यम्‌ (१.३.३) तथा तस्य लोपः (१.३.९) :</p>";
    display(0); 
}
/* GasiGasozca (6.1.110) */
if (arr($text,'/[eo][+]/') && in_array($so,array("Nasi!","Nas")))
{
    $text = two(array("e+","o+"),array("a"),array("e+","o+"),array(""),0);
    echo "<p class = sa >By GasiGasozca (6.1.110) :</p>";
    echo "<p class = sa >ङसिङसोश्च (६.१.११०) :</p>";
    display(0);
}
/* hrasvanadyApo nuT (7.1.54) */
if ( $so === "Am" && $numacira===0 && (arr($text,'/[aiufx][+]/')||$nadi===1||$Ap===1) && !in_array($fo,$sarvanama))
{
    $text = last(array("Am"),array("nAm"),0);
    echo "<p class = sa >By hrasvanadyApo nuT (7.1.54) :</p>";
    echo "<p class = sa >ह्रस्वनद्यापो नुट्‌ (७.१.५४) :</p>";
    display(0);
}
/* varSAbhvazca (6.8.84) */ 
if ($dhatu===1 && $first==="varzABU" && $pada==="pratyaya" && in_array($so,$acsup))
{
    $text = two(array("varzABU"),array("+"),array("varzABv"),array("+"),0);
    echo "<p class = sa >By varSAbhvazca (6.8.84) :</p>";
    echo "<p class = sa >वर्षाभ्वश्च (६.४.८४) :</p>";
    display(0);
}
/* dRnkarapunaHpUrvasya bhuvo yaN vaktavyaH (vA 4118) */ 
if ($dhatu===1 && in_array($fo,array("dfnBU","karaBU","kAraBU","punarBU")) && $pada==="pratyaya" && in_array($so,$acsup))
{
    $text = two(array("dfnBU","karaBU","kAraBU","punarBU"),array("+"),array("dfnBv","karaBv","kAraBv","punarBv"),array("+"),0);
    echo "<p class = sa >By dRnkarapunaHpUrvasya bhuvo yaN vaktavyaH (vA 4118) :</p>";
    echo "<p class = sa >दृन्करपुनःपूर्वस्य भुवो यण्‌ वक्तव्यः (वा ४११८) :</p>";
    display(0);
}
/* na bhUsudhiyoH (6.4.85) */
if ( $dhatu===1 && (arr($text,'/[B][U][+]/')||$fo==="suDI") && arr($text,'/[iuIU][+]['.flat($ac).']/') && $pada==="pratyaya")
{
    echo "<p class = sa >By na bhUsudhiyoH (6.4.85) :</p>";
    echo "<p class = sa >न भूसुधियोः (६.४.८५) :</p>";
    display(0);
    $nabhusu = 1;
} else { $nabhusu = 0; }
/* kvau luptaM na sthAnivat (vA 431) */
// Not displayed because it is difficult to teach sthnanivadbhav to machine now. Will come back to it if I can teach it some day.
/* aci znudhAtubhruvAM yvoriyaGuvaGau (6.4.77) */
// znu pending.
if (($dhatu===1||$fo==="BrU") && arr($text,'/[iuIU][+]['.flat($ac).']/') && $pada==="pratyaya" && ($asaMyogapUrva===0 || ($asaMyogapUrva===1 && anekAca($fo)===false )))
{
    $text = two(array("i","I","u","U"),array("+"),array("iy","iy","uv","uv"),array("+"),0);
    echo "<p class = sa >By aci znudhAtubhruvAM yvoriyaGuvaGau (6.4.77) :</p>";
    echo "<p class = hn >gatikAraketarapUrvapadasya yaN neSyate (vA 5034) mandates that eranekAco... rule doesn't apply in cases where the pUrvapada is neither gati nor kAraka. iyaG or uvaG is applied in that case. :</p>";
    echo "<p class = sa >अचिश्नुधातुभ्रुवां य्वोरियङुवङौ (६.४.७७) :</p>";
    echo "<p class = hn >गतिकारकेतरपूर्वपदस्य यण्‌ नेष्यते (वा ५०३४) से गति / कारक से भिन्न पूर्वपद होने पर एरनेकाचो... सूत्र नहीं लागू होता । इयङ्‌ या उवङ्‌ की प्रवृत्ति होती है । :</p>";
    display(0);
}
/* eranekAco'saMyogapUrvasya (6.4.82) */ 
if ($dhatu===1 && arr($text,'/[iI][+]['.flat($ac).']/') && $pada==="pratyaya" && anekAca($fo) && $asaMyogapUrva===1 && $nabhusu===0)
{
    $text = two(array("i","I"),array("+"),array("y","y"),array("+"),0);
    echo "<p class = sa >By eranekAco'saMyogapUrvasya (6.4.82) :</p>";
    echo "<p class = hn >gatikAraketarapUrvapadasya yaN neSyate (vA 5034) mandates that this rule doesn't apply in cases where the pUrvapada is neither gati nor kAraka. iyaG or uvaG is applied in that case. :</p>";
    echo "<p class = sa >एरनेकाचोऽसंयोगपूर्वस्य (६.४.८२) :</p>";
    echo "<p class = hn >गतिकारकेतरपूर्वपदस्य यण्‌ नेष्यते (वा ५०३४) से गति / कारक से भिन्न पूर्वपद होने पर यह सूत्र नहीं लागू होता । इयङ्‌ या उवङ्‌ की प्रवृत्ति होती है । :</p>";
    display(3);
}
/* oH supi (6.4.83) */ 
if ($dhatu===1 && arr($text,'/[uU][+]['.flat($ac).']/') && $pada==="pratyaya" && anekAca($fo) && $asaMyogapUrva===1 && $nabhusu===0 && in_array($so,$sup))
{
    $text = two(array("u","U"),array("+"),array("v","v"),array("+"),0);
    echo "<p class = sa >By oH supi (6.4.83) :</p>";
    echo "<p class = hn >gatikAraketarapUrvapadasya yaN neSyate (vA 5034) mandates that this rule doesn't apply in cases where the pUrvapada is neither gati nor kAraka. iyaG or uvaG is applied in that case. :</p>";
    echo "<p class = sa >ओः सुपि (६.४.८३) :</p>";
    echo "<p class = hn >गतिकारकेतरपूर्वपदस्य यण्‌ नेष्यते (वा ५०३४) से गति / कारक से भिन्न पूर्वपद होने पर यह सूत्र नहीं लागू होता । इयङ्‌ या उवङ्‌ की प्रवृत्ति होती है । :</p>";
    display(0);
}
/* gatikAraketarapUrvapadasya yaN neSyate (vA 5034) */
// This is attached with eranekAco... So, trying to put a note and making the iyaG and yaN optional.
/* halGyAbbhyo dIrghAtsutisyapRktaM hal (6.1.68) and apRkta ekAlpratyayaH (1.2.41) */
// GyAp pending. only hal handled now.
if ((arr($text,'/['.pc('hl').'][+][sts]$/') || $GI===1 || $Ap===1 )&& in_array($so,array("su!","ti","si")))
{
    $text = two(array("+"),array("s","t"),array("+"),array("",""),0);
    echo "<p class = sa >By halGyAbbhyo dIrghAtsutisyapRktaM hal (6.1.68) and apRkta ekAlpratyayaH (1.2.41) :</p>";
    echo "<p class = sa >हल्ङ्‍याब्भ्यो दीर्घात्सुतिस्यपृक्तं हल्‌ (६.१.६८) तथा अपृक्त एकाल्प्रत्ययः (१.२.४१) :</p>";
    display(0);
}
/* Rta ut (6.1.111) */
if ( (arr($text,'/[fx][+][a]/')) && in_array($so,array("Nasi!","Nas")) && $pada==="pratyaya")
{
    $text = two(array("f","x"),array("+a"),array("ur","ul"),array("+"),0);
    echo "<p class = sa >By Rta ut (6.1.111) :</p>";
    echo "<p class = sa >ऋत उत्‌ (६.१.१११) :</p>";
    display(0);
}
/* auto'mzasoH (6.1.93) */
if (sub(array("o"),array("+"),array("a"),0) && in_array($so,array("am","Sas")))
{  
    $text = two(array("o"),array("+a"),array(""),array("+A"),0);
    echo "<p class = sa >By auto'mzasoH (6.1.93) :</p>";
    echo "<p class = sa >औतोऽम्शसोः (६.१.९३) :</p>";
    display(0);
}
/* aco JNiti (7.2.115) */
if ((arr($text,'/['.flat($ac).'][+][JR]/')||arr($text,'/[a][+][*][JR]$/')||$Nidvat===1||$Nidvat1===1) && $pada==="pratyaya" )
{ 
    $text = two($ac,array("+"),vriddhi($ac),array("+"),0);
    echo "<p class = sa >By aco JNiti (7.2.115) :</p>";
    echo "<p class = sa >अचो ञ्णिति (७.२.११५) :</p>";
    display(0);
}



// Creating a do-while loop for sapAdasaptAdhyAyI. There is no order of application of rules in sapAdasaptAdhyAyI. 
// Therefore the cause for application may arise after application of any rule. So created a do-while loop which will check till the input and output are the same i.e. there is no difference after the application of all the sUtras.
$start = 1;
do 
{
$original = $text ;
/* ato bhisa ais (7.1.9) */
if (arr($text,'/[a][+]/') && $so === "Bis"  && $start ===1 )
{
    $second = str_replace("Bis","Es",$second);
    $text = two(array("a"),array("Bis"),array("a"),array("Es"),0);
    echo "<p class = sa >By ato bhisa ais (7.1.9) :</p>";
    echo "<p class = sa >अतो भिस ऐस्‌ (७.१.९) :</p>";
    display(0);
}
/* Ami sarvanAmnaH suT (7.1.15) */
if ( $so === "Am" && in_array($fo,$sarvanama) && $start ===1)
{
    $text = last(array("Am"),array("sAm"),0);
    echo "<p class = sa >By Ami sarvanAmnaH suT (7.1.15) :</p>";
    echo "<p class = sa >आमि सर्वनाम्नः सुट्‌ (७.१.१५) :</p>";
    display(0); $sut=1;
} else { $sut=0;}

/* osi ca (7.3.104) */
if ($so === "os" && arr($text,'/[a][+]/') && $start ===1)
{
    $text = two(array("a"),array($second),array("e"),array($second),0);
    echo "<p class = sa >By osi ca (7.3.104) :</p>";
    echo "<p class = sa >ओसि च (७.३.१०४) :</p>";
    display(0);
}
/* nAmi (6.4.3) and nR ca (6.4.6) */
if (arr($text,'/['.flat($ac).'][+][n][A][m]$/') && $start ===1 && !in_array($fo,$sarvanama))
{
    if(arr($text,'/[n][f][+][n][A][m]$/'))
    {
    $text = two(array("nf"),array("nAm"),array("nF"),array("nAm"),1);
    echo "<p class = sa >By nR ca (6.4.6) :</p>";
    echo "<p class = sa >नृ च (६.४.६) :</p>";
    display(0);        
    }
    else
    {
    $text = two($ac,array("nAm"),array("A","A","I","I","U","U","F","F","F","F","e","E","o","O"),array("nAm"),0);
    echo "<p class = sa >By nAmi (6.4.3) :</p>";
    echo "<p class = sa >नामि (६.४.३) :</p>";
    display(0);        
    }
 $nami = 1;
} else { $nami = 0; }
/* bahuvacane jhalyet (7.3.103) */
if ((in_array($so,array("Byas","sup")) || $sut===1) && arr($text,'/[a][+]/') && $start ===1)
{
    $text = two(array("a"),array("Byas","su","sAm"),array("e"),array("Byas","su","sAm"),0);
    echo "<p class = sa >By bahuvacane jhalyet (7.3.103) :</p>";
    echo "<p class = sa >बहुवचने झल्येत्‌ (७.३.१०३) :</p>";
    display(0); $bahuvacane = 1;
} else { $bahuvacane = 0; }
/* supi ca (7.3.102) */
if (in_array($so,$sup) && arr($text,'/[a][+]['.pc('yY').']/') && $start === 1 && $bahuvacane === 0 && $nami === 0 && $Ne!==1)
{
    $text = two(array("a"),array($second),array("A"),array($second),0);
    echo "<p class = sa >By supi ca (7.3.102) :</p>";
    echo "<p class = sa >सुपि च (७.३.१०२) :</p>";
    display(0);
}
if ($Ne===1 && $start === 1)
{
    $text = one(array("a+ya"),array("A+ya"),0);
    echo "<p class = sa >By supi ca (7.3.102) :</p>";
    echo "<p class = sa >सुपि च (७.३.१०२) :</p>";
    display(0);
}
/* pragRhya section */
/* plutapragRhyA aci nityam (6.1.125) */
// There is no definition of pluta / pragRhya here. So we will code that as and when case arises.
/* iko'savarNe zAkalyasya hrasvazca (6.1.127) */ // Right now coded for only dIrgha. Clarify wheter the hrasva preceding also included?
$ik = array("i","I","u","U","f","F","x","X");
$nonik = array("a","A","e","E","o","O");
if (sub($ik,$nonik,blank(0),0) && $pada==="pada")
{
$text = two(array("i","I"),array("a","A","u","U","f","F","x","X","e","o","E","O"),array("i ","i "),array("a","A","u","U","f","F","x","X","e","o","E","O"),1);
$text = two(array("u","U"),array("a","A","i","I","f","F","x","X","e","o","E","O"),array("u ","u "),array("a","A","i","I","f","F","x","X","e","o","E","O"),1);
$text = two(array("f","F"),array("a","A","u","U","i","I","e","o","E","O"),array("f ","f "),array("a","A","u","U","i","I","e","o","E","O"),1);
$text = two(array("x","X"),array("a","A","u","U","i","I","e","o","E","O"),array("x ","x "),array("a","A","u","U","i","I","e","o","E","O"),1);
echo "<p class = sa >By iko'savarNe zAkalyasya hrasvazca (6.1.127) :</p>
    <p class = hn >Note that this will not apply in samAsa. Also when followed by a 'sit' pratyaya, it will not apply. e.g. pArzva</p>";
echo "<p class = sa >इकोऽसवर्णे शाकल्यस्य ह्रस्वश्च (६.१.१२७) :</p>
    <p class = hn >समास व सित्‌ प्रत्यय परे होने पर यह लागू नहीं होता । जैसे कि पार्श्व ।</p>";
display(0);
}
/* upasargAdRti dhAtau (6.1.11) and vA supyApizaleH (6.1.12) */
$akarantaupasarga = array("pra","apa","ava","upa",);
$changedupasarga = array("prAr","apAr","avAr","upAr");
$changedupasarga1 = array("prar","apar","avar","upar");
$changedupasarga2 = array("prAl","apAl","avAl","upAl");
$changedupasarga3 = array("pral","apal","aval","upal");
if ((sub($akarantaupasarga,$verbs_ru,blank(0),0) && !sub(array("prafRa"),blank(0),blank(0),0))||sub($akarantaupasarga,array("xkArIy"),blank(0),0))
{
    if (arr($text,'/[I][y]/'))
    {
            $text = two($akarantaupasarga,$verbs_ru,$changedupasarga,$verbs_changed,1);
    }
    else
    {
            $text = two($akarantaupasarga,$verbs_ru,$changedupasarga,$verbs_changed,0);
    }
$text = two($akarantaupasarga,array("xkArIy"),$changedupasarga2,array("kArIy"),1);
$text = two($akarantaupasarga,array("xkArIy"),$changedupasarga3,array("kArIy"),0);
echo "<p class = sa >By upasargAdRti dhAtau (6.1.11) and vA supyApizaleH (6.1.12) :</p>";
echo "<p class = hn >In case akArAnta upasarga is followed by RkArAdi nAmadhAtu, there is optional vRddhi ekAdeza. If there is dIrgha RUkAra at the start of dhAtu or nAmadhAtu, upasargAdRti dhAtau and vA supyApizaleH don't apply. iko yaNaci and uraNraparaH apply.</p>";
echo "<p class = sa >उपसर्गादृति धातौ (६.१.११) तथा वा सुप्यापिशलेः (६.१.१२) :</p>";
echo "<p class = hn >अकारान्त उपसर्ग से ऋकारादि नामधातु परे होने पर विकल्प से वृद्धि एकादेश होता है । यदि धातु या नामधातु ॠकार से आरंभ होता है, तब उपसर्गादृति धातौ तथा वा सुप्यापिशलेः लागू नहीं होते हैं । अतः इको यणचि व उरण्रपरः ही लागू होते हैं ।</p>";
display(0); $upas = 1;
} else { $upas = 0; }
/* RtyakaH (6.1.128) */
$ak = array("a","A","i","I","u","U","f","F","x","X"); 
$akrt = array("a ","A ","i ","I ","u ","U ","f ","F ","x ","X "); 
if (arr($text,'/['.flat($ak).'][+][fx]/') && $start===1 && $pada ==="pada" && $upas ===0 )
{
if (sub($ak,array("f","x"),blank(0),0))
{
$text = two ($ak,array("f","x"),$akrt,array("f","x"),1);
echo "<p class = sa >By RtyakaH (6.1.128) :</p>
    <p class = hn >Note: This applies only to padAnta. </p>";
echo "<p class = sa >ऋत्यकः (६.१.१२८) :</p>
    <p class = hn >Note: This applies only to padAnta. </p>";
display(0);
}
}
if ($upas === 1)
{
    echo "<p class = hn >RtyakaH is barred by upasargAdRti dhAtau. </p>
    <p class = hn >ऋत्यकः उपसर्गादृति धातौ से बाधित हुआ है ।</p><hr>";
}
/* vAkyasya TeH pluta udAttaH (8.2.82) */
// This is adhikArasutra. Nothing to code here.
/* pratyabhivAde'zUdre (8.2.83) */
/* dUrAddhUte ca (8.2.84) */
/* haihe prayoge haihayoH (8.2.85) */
/* guronanRto'nantyasyApyekaikasya prAcAm (8.2.86) */
/* aplutavadupasthite (6.1.129) */
/* I3 cAkravarmaNasya (6.1.130) */
// These two are not possible to code, because it will depend on the speaker's choice.
/* IdUdeddvivacanaM pragRhyam (1.1.11) */
// not possible to code till we get the word forms of all words and check whether it is dvivacana or not. Pending
/* adaso mAt (1.1.12) */
if (sub(array("amI"),blank(0),blank(0),0) && $fo === "amI" && $start===1)
{
$text = two (array("amI"),$ac,array("amI "),$ac,1);
echo "<p class = sa >By adaso mAt (1.1.12) :</p>";
echo "<p class = sa >अदसो मात्‌ (१.१.१२) :</p>";
display(0);
}
if (sub(array("amU"),blank(0),blank(0),0)&& $fo === "amU" &&$start===1)
{
$text = two (array("amU"),$ac,array("amU "),$ac,1);
echo "<p class = sa >By adaso mAt (1.1.12) :</p>";
echo "<p class = sa >अदसो मात्‌ (१.१.१२) :</p>";
display(0);
}
/* ze (1.1.13) */
// Not possible to know whether one form has ze or not.
/* nipAta ekAjanAG (1.1.14) */
$nipata = array("a","A","i","I","u","U","e","E","o","O");
$nipata1 = array("a ","A ","i ","I ","u ","U ","e ","E ","o ","O "); 
if (in_array($first,$nipata) && sub(array($first),$ac,blank(0),0) && $start===1)
{
$text = two ($nipata,$ac,$nipata1,$ac,0);
echo "<p class = sa >By nipAta ekAjanAG (1.1.14) :</p>";
echo "<p class = sa >निपात एकाजनाङ्‌ (१.१.१४) :</p>";
display(0);
}
/* ot (1.1.15) */
$ot = array("o","aho","ho","utAho","aTo");
$ot1 = array("o ","aho ","ho ","utAho ","aTo ");
if (in_array($first,$ot) && sub(array($first),$ac,blank(0),0) && $start===1)
{
$text = two ($ot,$ac,$ot1,$ac,0);
echo "<p class = sa >By ot (1.1.15) :</p>";
echo "<p class = sa >ओत्‌ (१.१.१५) :</p>";
display(0);
}
/* sambuddhau zAkalyasyetAvanArSe (1.1.16) */
if (arr($text,'/[o][+]/') && $second === "iti" && $start===1)
{
$text = two(array($first),$ac,array($first." "),$ac,1);
echo "<p class = sa >By sambuddhau zAkalyasyetAvanArSe (1.1.16) :</p>
    <p class = hn >Note: This rule will apply only in case the 'o'kAra at the end of the first word is for sambuddhi and the 'iti' is anArSa (of non-vedic origin).</p>";
"<p class = sa >सम्बुद्धौ शाकल्यस्येतावनार्षे (१.१.१६) :</p>
    <p class = hn >यह नियम तभी लागू होगा जब प्रथम शब्द के अन्त का ओकार सम्बुद्धि के लिए हो और अनार्ष 'इति' शब्द उसके परे हो ।</p>";
display(0);
}
/* UYaH (1.1.17) */
if ($first === "u" && $second === "iti" && $start===1)
{
$text = two(array("u"),array("iti"),array("u "),array("iti"),1);
echo "<p class = sa >By uYaH (1.1.17) :</p>";
echo "<p class = sa >उञः (१.१.१७) :</p>";
display(0);
}
/* U! (1.1.18) */ // Here ! has been used for anunAsika.
if ($first === "u" && $second === "iti" && $start===1)
{
$text = two(array("u"),array("iti"),array("U! "),array("iti"),1);
echo "<p class = sa >By U! (1.1.17) :</p>";
echo "<p class = sa >ऊँ (१.१.१७) :</p>";
display(0);
}
/* maya uYo vo vA (8.3.33) */
if (sub(array("Sam","kim","tvam","tad"),array("u"),$ac,0))
{
$text = three(array("Sam","kim","tvam","tad"),array("u"),$ac,array("Sam","kim","tvam","tad"),array("v"),$ac,1);
echo "<p class = sa >By maya uYo vo vA (8.3.33) :</p>";
echo "<p class = sa >मय उञो वो वा (८.३.३३) :</p>";
display(0);
}
/* IdUtau ca saptamyarthe (1.1.19) */
/*$idut = array("I","U"); $idut1 = array("I ","U ");
if (preg_match('/[IU]$/',$first) && sub(array("I","U"),$ac,blank(0),0) && $pada ==="pada")
{
$text = two($idut,$ac,$idut1,$ac,1);
echo "<p class = sa >By IdUtau ca saptamyarthe (1.1.19) :</p>";
echo "<p class = hn >N.B.: This will apply only in case the I/U at the end of the first word have been used in sense of saptamI vibhakti. Otherwise this pragRhyatva will not be there.</p>";
echo "<p class = sa >ईदूतौ च सप्तम्यर्थे (१.१.१९) :</p>";
echo "<p class = hn >यदि प्रथम पद के अन्त में ई / ऊ सप्तमी के अर्थ में प्रयुक्त हुए हों तभी यह नियम लागू होगा ।</p>";
display(0);
}*/
/* zakandhvAdiSu pararUpaM vAcyam (vA 3632) */
$shakandhu1 = array("Saka","karka","kula","manas","hala","lANgala","patan","mfta");
$shakandhu2 = array("anDu","anDu","awA","IzA","IzA","IzA","aYjali","aRqa");
$shakandhu = array("Sak","kark","kul","man","hal","lANgal","pat","mArt");
if (sub($shakandhu1,$shakandhu2,blank(0),0))
{
$text = two($shakandhu1,$shakandhu2,$shakandhu,$shakandhu2,0);
echo "<p class = sa >By zakandhvAdiSu pararUpaM vAcyam (vA 3632) :</p>";
echo "<p class = sa >शकन्ध्वादिषु पररूपं वाच्यम्‌ (वा ३६३२) :</p>";
display(0);
}
$shakandhu1 = array("sIman","sAra");
$shakandhu2 = array("anta","aNga");
$shakandhu = array("sIm","sAr");
if (sub($shakandhu1,$shakandhu2,blank(0),0))
{
$text = two($shakandhu1,$shakandhu2,$shakandhu,$shakandhu2,1);
$text = one(array("sIman+ant","sAra+aNg"),array("sIm+Ant","sAr+ANg"),0);
echo "<p class = sa >By zakandhvAdiSu pararUpaM vAcyam (vA 3632) :</p>";
echo  "<p class = hn >Note: the sImanta - kezaveSa and sAraGga - pazu/pakSI - Then only this will apply.</p>";
echo "<p class = sa >शकन्ध्वादिषु पररूपं वाच्यम्‌ (वा ३६३२) :</p>";
echo  "<p class = hn >यदि सीमन्त केशवेश के अर्थ में और सारङ्ग पशु-पक्षी के अर्थ में प्रयुक्त हुए हो, तभी यह नियम लागू होता है ।</p>";
display(0);
}
/* omAGozca (6.1.95) */ 
$aag = array("om","OM","Aj","Acy","AYc","Anakt","Att","As","er","Eray","okz","ArcC","Arpit","Ohyat","oQ","arSyAt");
if (sub(array("a","A"),$aag,blank(0),0))
{ 
$text = two(array("a","A"),array("om","OM"),blank(2),array("om","om"),0);
$text = two(array("a","A"),$aag,blank(2),$aag,0);
echo "<p class = sa >By omAGozca (6.1.95) :</p>
    <p class = hn >The om or AG following the a,A gets converted to pararUpa. </p>";
echo "<p class = sa >ओमाङोश्च (६.१.९५) :</p>
    <p class = hn >अ/आ के परे यदि ओम्‌ या आङ्‌ हो तो पररूप होता है । </p>";
display(0);
}
/* ami pUrvaH (6.1.107) */
if ($so === "am" && sub(array("a","A","i","I","u","U","f","F","x"),array("am"),blank(0),0))
{
    $text = two(array("a","A","i","I","u","U","f","F","x"),array("am"),array("a","A","i","I","u","U","f","F","x"),array("m"),0);
    echo "<p class = sa >By ami pUrvaH (6.1.107) :</p>";
    echo "<p class = sa >अमि पूर्वः (६.१.१०७) :</p>";  $ato = 1;
    display(0);
}  
/* ATazca (6.1.90) */
// right now only ANnadyAH case is taken. Add the other cases as and when they arise.
if ((arr($text,'/[+][A][+]['.flat($ac).']/') && $ANnadyAH===1) )
{
$text = two(array("+A+"),$ac,array("+"),vriddhi($ac),0);
echo "<p class = sa >By ATazca (6.1.90) :</p>";
echo "<p class = sa >आटश्च :</p>";
display(0);
}
/* nAdici (6.1.104) */
$ic = array("i","I","u","U","f","F","x","X","e","o","E","O");
if (arr($text,'/[a][+]['.flat($ic).']/') && (in_array($so,$prathama)))
{
    echo "<p class = sa >By nAdici (6.1.104) :</p>
        <p class = hn >N.B. : This is exception to prathamayoH pUrvasavarNaH. </p>";
      echo "<p class = sa >नादिचि (६.१.१०४) :</p>
        <p class = hn >यह नियम प्रथमयोः पूर्वसवर्णः का अपवाद है ।</p>";
    display (0); $nadici = 1;
} else { $nadici = 0; }
/* dIrghAjjasi ca (6.1.105) */
if ((arr($text,'/[AIUFeEoO][+]['.flat($ic).']/')||((sub($dirgha,array("+"),array("as"),0)) && $so==="jas")) && (in_array($so,$prathama)))
{
    echo "<p class = sa >By dIrghAjjasi ca (6.1.105) :</p>
        <p class = hn >N.B. : This is exception to prathamayoH pUrvasavarNaH. </p>";
      echo "<p class = sa >दीर्घाज्जसि च (६.१.१०५) :</p>
        <p class = hn >यह नियम प्रथमयोः पूर्वसवर्णः का अपवाद है ।</p>";
    display (0); $nadici1 = 1;
} else { $nadici1 = 0; }
/* prathamayoH pUrvasavarNaH (6.1.102) */ 
// Not coded well. Please revisit.
$ak = array("a","A","i","I","u","U","f","F","x","X"); 
$akreplace = array("A","A","I","I","U","U","F","F","F","X");
if (sub($ak,array("a","O"),blank(0),0) && in_array($so,$prathama)  && $nadici === 0 && $nadici1 === 0)
{
    $text = two($ak,array("a","O"),$akreplace,array("",""),0);
    echo "<p class = sa >By prathamayoH pUrvasavarNaH (6.1.102) :</p>
        <p class = hn >N.B. : This applies to only in prathamA and dvitIyA vibhakti, and not in other cases. </p>";
    echo "<p class = sa >प्रथमयोः पूर्वसवर्णः (६.१.१०२) :</p>
        <p class = hn >यह प्रथमा और द्वितीया विभक्तियों में लागू होता है ।</p>";
    display (0); $prathamayoh = 1;
} else { $prathamayoh = 0; }
/* tasmAcChaso naH puMsi (6.1.103) */
if ($prathamayoh ===1 && $so === "Sas")
{
    $text = last(array("s"),array("n"),0);
    echo "<p class = sa >By tasmAcChaso naH puMsi (6.1.103) :</p>";
    echo "<p class = sa >तस्माच्छसो नः पुंसि (६.१.१०३) :</p>";  
    display(0); $tasmat = 1; $second = "an";
} else { $tasmat = 0; }
/* ato guNe (6.1.17) */
if (sub(array("a"),array("a","e","o"),blank(0),0) && $pada === "pratyaya"  )
{
    $text = two(array("a"),array("a","e","o"),blank(1),array("a","e","o"),0);
    echo "<p class = sa >By ato guNe (6.1.17) :</p>";
    echo "<p class = sa >अतो गुणे (६.१.१७) :</p>";  $ato = 1;
    display(0);
} else { $atogune = 0; } 
/* eGhrasvAtsambuddheH (6.1.69) and ekavacanaM sambuddhiH (2.3.49) */ // removed the last letter, not as in sutra. Look out for issues if any crops up.
if ($sambuddhi === 1 && $so === "su!" && (sub($hrasva,array("+"),array("s"),0)||sub(array("e","o"),array("+"),array("s"),0)))
{
    foreach ($text as $value)
    {
        $value1[] = substr($value,0,strlen($value)-1);
    }
    $text = $value1;
    $value1 = array();
    echo "<p class = sa >By eGhrasvAtsambuddheH (6.1.69) and ekavacanaM sambuddhiH (2.3.49) :</p>";
    echo "<p class = sa >एङ्ह्रस्वात्सम्बुद्धेः (६.१.६९) तथा एकवचनं सम्बुद्धेः (२.३.४९) :</p>";  $ato = 1;
    display(0); $eg = 1;
} else { $eg = 0; }
/* Rti savarNe R vA (vA 3640) and lRti savarNe lR vA (vA 3641) */
$ruti1 = array("f","F","x","X");
$ruti2 = array("f");
$lruti2 = array("x");
if (sub($ruti1,array("f","x"),blank(0),0))
{
$text = two($ruti1,array("f"),blank(count($ruti1)),$ruti2,1);
$text = two($ruti1,array("x"),blank(count($ruti1)),$lruti2,1);
echo "<p class = sa >By Rti savarNe R vA (vA 3640) and lRti savarNe lR vA (vA 3641) :</p>";
echo "<p class = sa >ऋति सवर्णे ऋ वा (वा ३६४०) तथा लृति सवर्णे लृ वा (वा ३६४१) :</p>";
display(0); $rutrut = 1;
} else { $rutrut = 0; } 
/* akaH savarNe dIrghaH (6.1.101) */ 
$ak1 = array("a","a","A","A","i","i","I","I","u","u","U","U","f","f","F","F","f","f","F","F","x","x","X","X","x","x","X","X");
$ak2 = array("a","A","a","A","i","I","i","I","u","U","u","U","f","F","f","F","x","X","x","X","f","F","f","F","x","X","x","X");
if (sub($ak1,$ak2,blank(28),1) && $atogune !== 1)
{
$text = two(array("a","A"),array("a","A"),array("A","A"),blank(2),0);
$text = two(array("i","I"),array("i","I"),array("I","I"),blank(2),0);
$text = two(array("u","U"),array("u","U"),array("U","U"),blank(2),0);
$text = two(array("f","F","x","X"),array("f","F","x","X"),array("F","F","F","F"),blank(4),0);
$text = two(array("x","X"),array("x","X"),array("F","F"),blank(2),0);
echo "<p class = sa >By akaH savarNe dIrghaH (6.1.101) :</p>";
echo "<p class = sa >अकः सवर्णे दीर्घः (६.१.१०१) :</p>";
display(0);
}
/*iko yaNaci (6.1.77) */
if(sub(array('i','I','u','U'),prat('ac'),blank(0),0))
{
$text = two(array('i','I','u','U'),prat('ac'),array('y','y','v','v'),prat('ac'),0);
echo "<p class = sa >By iko yaNaci (6.1.77) :</p>";
echo "<p class = sa >इको यणचि (६.१.७७) :</p>";
display(3); $iko=1;
} else { $iko=0; }
if(sub(array("f","F","x","X"),prat('ac'),blank(0),0))
{
$text = two(array("f","F","x","X"),prat('ac'),array("r","r","l","l"),prat('ac'),0);
echo "<p class = sa >By iko yaNaci (6.1.77) :</p>";
echo "<p class = sa >इको यणचि (५.१.७७) :</p>";
$sthanivadbhav = 1;
display(3); $iko1=1;
}
else
{
$sthanivadbhav = 0; $iko1=0;
}
/* sarvatra vibhASA goH (6.1.122) */
$go = array("go"); $aonly = array("a");
if(sub($go,$aonly,blank(0),0) && $pada ==="pada")
{
$text = two($go,$aonly,array("go "),$aonly,1);
echo "<p class = sa >By sarvatra vibhASA goH (6.1.122)</p>
    <p class = hn >it is optionally kept as prakRtibhAva :</p>";
echo "<p class = sa >सर्वत्र विभाषा गोः (६.१.१२२)</p>
    <p class = hn >पाक्षिक रूप से प्रकृतिभाव भी होता है ।</p>";
display(0); $gogo = 1;
} else { $gogo = 0; } 
/* avaG sphoTAyanasya (6.1.123) */
if (sub($go,prat('ac'),blank(0),0)  && $pada ==="pada")
{
$text = two($go,prat('ac'),array('gava'),prat('ac'),1);
echo "<p class = sa >By avaG sphoTAyanasya (6.1.123) </p>
    <p class = hn >it is optionally converted to avaG :</p>";
echo "<p class = sa >अवङ्‌ स्फोटायनस्य (६.१.१२३) </p>
    <p class = hn >पाक्षिक रूप से अवङ्‌ भी होता है ।</p>";
display(0); $gogo1 = 1;
} else { $gogo1 = 0; }
/* indre ca (6.1.124) */
if (sub($go,array("indra"),blank(0),0)  && $pada ==="pada")
{
$text = two($go,array("indra"),array('gava'),array("indra"),0);
echo "<p class = sa >by indre ca (6.1.124) :</p>";
echo "<p class = sa >इन्द्रे च (६.१.१२४) :</p>";
display(0); $gogo2 = 1;
} else { $gogo2 = 0; }
/* eGaH padAntAdati (6.1.109) */
if (sub(array("e","o"),array("a"),blank(0),0)  && $pada ==="pada")
{
    $text = two(prat('eN'),array("a"),prat('eN'),array("'"),0);
    echo "<p class = sa >By eGaH padAntAdati (6.1.109) : </p>";
    echo "<p class = sa >एङः पदान्तादति (६.१.१०९) : </p>";
    display(0);
}
/* eco'yavAyAvaH (7.1.78) */
$ayavayavah = array("ay","av","Ay","Av");
if (sub(prat('ec'),prat('ac'),blank(0),0))
{
$text = two(prat('ec'),prat('ac'),$ayavayavah,prat('ac'),0);
echo "<p class = sa >By echo'yavAyAvaH (7.1.78) :</p>";
echo "<p class = sa >एचोऽयवायावः (७.१.७८) :</p>";
display(0);
$bho = 1;
} else { $bho = 0; }
/* vAnto yi pratyaye (6.1.71), goryutau CandasyupasaMkhyAnam (vA 3543), adhvaparimANe ca (vA 3544) dhAtostannimittasyaiva (6.1.80) */
$o = array("o","O"); $oo = 'oO'; $y = array("y"); $ab = array("av","Av");
$dhato = array("urRo","ro","no","sno","kzo","kzRo","Do","Dro","do","dro","sro","so","ko","Go","qo","cyo","jyo","pro","plo","ro","ho","Sro","hno","dyo","sko","po","lo","kno","mo","Bo","urRO","rO","snO","kzO","kzRO","DO","DrO","dO","drO","srO","sO","kO","GO","qO","cyO","jyO","prO","plO","rO","hO","SrO","hnO","dyO","skO","pO","lO","knO","mO","BO","Co","zo","So");
$dhato1 = array("urRa","ra","na","sna","kza","kzRa","Da","Dra","da","dra","sra","sa","ka","Ga","qa","cya","jya","pra","pla","ra","ha","Sra","hna","dya","ska","pa","la","kna","ma","Ba","urRA","rA","snA","kzA","kzRA","DA","DrA","dA","drA","srA","sA","kA","GA","qA","cyA","jyA","prA","plA","rA","hA","SrA","hnA","dyA","skA","pA","lA","knA","mA","BA","Ca","za","Sa");
$text1 = $text;
if (sub($dhato,$y,blank(0),0) && $pada ==="pratyaya" && $start ===1)
{
$text = two($dhato,$y,$dhato1,array("vy"),0);
    echo "<p class = sa >By dhAtostannimittasyaiva (6.1.77)  : </p>";
    echo "<p class = sa >धातोस्तन्निमित्तस्यैव (६.१.७७) : </p>";
    display(0);
} 
if (sub($o,$y,blank(0),0) && $pada ==="pratyaya" && !sub($dhato,$y,blank(0),0))
{
    if (sub(array("gav"),$y,blank(0),0))
    {
    $text = two($o,$y,$ab,$y,0);
    echo "<p class = sa >By vAnto yi pratyaye (6.1.71), goryutau CandasyupasaMkhyAnam (vA 3543), adhvaparimANe ca (vA 3544)  : </p>
        <p class = hn > If the 'y' following 'o/O' belongs to a pratyaya or the word 'go' is followed by 'yuti' in Chandas/ as a measure of distance (vA 3543, 3544).</p>";
    echo "<p class = sa >वान्तो यि प्रत्यये (६.१.७१), गोर्यूतौ छन्दस्युपसंख्यानम्‌ (वा ३५४३), अध्वपरिमाणे च (वा ३५४४)  : </p>
        <p class = hn > यकारादि प्रत्यय के परे रहते या वैदिक भाषा / अध्वपरिमाण के अर्थ में यूति शब्द परे हो तब यह नियम लागू होता है ।</p>";        
    }
    elseif (sub(array("o","O"),array("yat"),blank(0),0))
    {
    echo "<p class = sa >By dhAtostannimittasyaiva (6.1.77)  : </p>";
    echo "<p class = hn >Here the 'o' is because of 'y'. Therefore This doesn't apply. </p>";
    echo "<p class = sa >धातोस्तन्निमित्तस्यैव (६.१.७७) :</p>";                
    echo "<p class = hn >ओयते में ओकार यकारनिमित्तक नहीं होने के कारण इस सूत्र से अवादेश नहीं हुआ ।</p>";                
    }
    else
    {
    $text = two($o,$y,$ab,$y,0);
    echo "<p class = sa >By vAnto yi pratyaye (6.1.71) : </p>";
    echo "<p class = sa >वान्तो यि प्रत्यये (६.१.७१) :</p>";        
    }
    display(0);
}
/* kSayyajayyau zakyArthe (6.1.81)*/
if (sub(array("kze"),array("ya"),blank(0),0)  && $pada ==="pratyaya" && $start === 1)
{
    $text = two(array("kze"),array("ya"),array("kzay"),array("ya"),1);
    echo "<p class = sa >By kSayyajayyau zakyArthe (6.1.81) :</p>
        <p class = hn >If the word is to be used in the meaning of 'being capable of', then only it will be क्षय्य.</p>";
    echo "<p class = sa >क्षय्यजय्यौ शक्यार्थे (६.१.८१) :</p>
        <p class = hn >यदि क्षय कर सकने के  अर्थ में प्रयोग होता है तभी क्षय्य होगा ।</p>";
    display(0);    
}
if (sub(array("je"),array("ya"),blank(0),0) && $pada ==="pratyaya" && $start === 1)
{
        $text = two(array("je"),array("ya"),array("jay"),array("ya"),1);
    echo "<p class = sa >By kSayyajayyau zakyArthe (6.1.81) :</p>
        <p class = hn >If the word is to be used in the meaning of 'being capable of', then only it will be जय्य.</p>";
    echo "<p class = sa >क्षय्यजय्यौ शक्यार्थे (६.१.८१) :</p>
        <p class = hn >यदि जय कर सकने के  अर्थ में प्रयोग होता है तभी जय्य होगा ।</p>";
    display(0);
}
/* krayyastadarthe (6.1.82) */
if (sub(array("kre"),array("ya"),blank(0),0)  && $pada ==="pratyaya" && $start ===1)
{
    
    $text = two(array("kre"),array("ya"),array("kray"),array("ya"),1);
    echo "<p class = sa >By krayyastadarthe (6.1.82) :</p>
        <p class = hn >If the word is to be used in the meaning of 'for sale', then only it will be क्रय्य.</p>";
     echo "<p class = sa >क्रय्यस्तदर्थे (६.१.८२) :</p>
        <p class = hn >यदि बेचने के लिये रखा हुआ इस अर्थ में प्रयोग हो तभी क्रय्य शब्द बनता है </p>";
    display(0);
}
/* khyatyAtparasya (6.1.112) */
if (arr($text,'/[Kt][y][+][a]/')  && arr(array($fo),'/[Kt][iI]$/') && in_array($so,array("Nasi!","Nas")) && $pada==="pratyaya")
{
    $text = three(array("Ky","ty"),array("+"),array("a"),array("Ky","ty"),array("+"),array("u"),0);
    echo "<p class = sa >By khyatyAtparasya (6.1.112) :</p>";
    echo "<p class = sa >ख्यत्यात्परस्य (६.१.११२) :</p>";
    display(0);
}


/* Exceptions to sasajuSo ruH */
/* etattadoH sulopo'konaJsamAse hali (6.1.132) */
if (sub(array("sa","eza"),array("s"),$hl,0)  && !sub(array("asa","anEza"),array("s"),$hl,0))
{
    $text = three(array("sa","eza"),array("s"),$hl,array("sa","eza"),array(" "),$hl,1);
    echo "<p class = sa >By etattadoH sulopo&konaJsamAse hali (6.1.132) :</p>";
    echo "<p class = sa >एतत्तदोः सुलोपोऽकोऽनञ्समासे हलि (६.१.१३२)) :</p>";
    display(0);
}
/* so'ci lope cetpAdapUraNam (6.1.134) */
if (sub(array("sa"),array("s"),$ac,0))
{
    $text = three(array("sa"),array("s"),$ac,array("sa"),array(""),$ac,1);
    echo "<p class = sa >so'ci lope cetpAdapUraNam (6.1.134) :</p>
        <p class = hn >N.B. : There is difference of opinion here. vAmana thinks that it applies only to RkpAda. Others think that it applies to zlokapAda also e.g. 'saiSa dAzarathI rAmaH'.</p>";
    echo "<p class = sa >सोऽचि लोपे चेत्पादपूरणम्‌ (६.१.१३४) :</p>
        <p class = hn >यहाँ मतान्तर है । वामन के मत में यह केवल ऋक्पाद में लागू होता है । अन्यों के मत में यह श्लोकपाद में भी लागू होता है । जैसे कि सैष दाशरथी रामः ।</p>";
    display(0);
}
/* vasusraMsudhvaMsvanaDuhAM daH (8.2.72) */
$vasu = array("sraMs","DvaMs","anaquh");
if (sub($vasu,blank(0),blank(0),0)  && $pada ==="pada")
{
    $text = one($vasu,array("vad","srad","Dvad","anaqud"),0);
    echo "<p class = sa >By vasusraMsudhvaMsvanaDuhAM daH (8.2.72) :</p>";
     echo "<p class = sa >वसुस्रंसुध्वंस्वनडुहां दः (८.२.७२) :</p>";
    display(0); 
}
if ((sub(array("vidvas","sedivas","uzivas","Suzruvas","upeyivas","anASvas"),blank(0),blank(0),0))  && $pada ==="pada")
{
    $text = one(array("vidvas","sedivas","uzivas","Suzruvas","upeyivas","anASvas"),array("vidvad","sedivad","uzivad","Suzruvad","upeyivad","anASvad"),0);
    echo "<p class = sa >By vasusraMsudhvaMsvanaDuhAM daH (8.2.72) :</p>
        <p class = hn >N.B. : If 'vas' is used in sense of vasupratyayAnta as in 'vidvas', then only this conversion takes place. Not in cases like 'zivas'.</p>";
     echo "<p class = sa >वसुस्रंसुध्वंस्वनडुहाः दः (८.२.७२) :</p>
        <p class = hn >यदि वसुप्रत्ययान्त शब्द जैसे कि विद्वस्‌ इत्यादि में यह नियम लागू होता है । शिवस्‌ जैसे शब्दों में नहीं ।</p>";
   display(0); 
}
/* rAtsasya (8.2.24) */
if ((arr($text,('/[r][+][s]$/')) && $pada === "pratyaya") || (arr($text,('/[r][s][+]/')) && $pada === "pada") )
{
    $text = one(array("r+s"),array("r"),0);
    $text = two(array("rs"),array("+"),array("r"),array("+"),0);
    echo "<p class = sa >By rAtsasya (8.2.24) :</p>"; 
    echo "<p class = sa >रात्सस्य (८.२.२४) :</p>";
display(0);
}
/* saMyogAntasya lopaH (8.2.23) */ 
// coding pending because not clear. And also 'yaNaH pratiSedho vAcyaH' prohibits its application.
$samyoga = '/(['.pc('hl').'])([+])(['.pc('hl').'])$/';
$samyoga1 = '/(['.pc('hl').'])(['.pc('hl').'])$/';
$samyoga2 = '/(['.pc('hl').'])(['.pc('hl').'])([+])/';
$sam = '$1$2';
$sam1 = '$1';
$sam2 = '$1$3';
if ($iko!==1 && $iko1!==1 &&((arr($text,'/['.pc('hl').'][+]['.pc('hl').']$/')||arr($text,'/['.pc('hl').']['.pc('hl').']$/') ) || (arr($text,'/['.pc('hl').']['.pc('hl').'][+]/') && $pada==="pada")))
{
    foreach ($text as $value)
    {
        $value1[] = preg_replace($samyoga,$sam,$value);
        $value1[] = preg_replace($samyoga1,$sam1,$value);
        $value1[] = preg_replace($samyoga2,$sam2,$value);
    }    
    $text = array_diff($value1,$text);
    $text = array_unique($text);
    $value1 = array();
    echo "<p class = sa >By saMyogAntasya lopaH (8.2.23) :</p>";
    echo "<p class = sa >संयोगान्तस्य लोपः (८.२.२३) :</p>";
    display(0);            
}
/* sasajuSo ruH (8.2.66) */
if (arr($text,'/[Hs][+]/') && $start===1  && $pada ==="pada" && $eg !== 1 && $tasmat !==1)
{
    $text = one(array("H+","s+"),array("r@+","r@+"),0);
    echo " <p class = hn >You have entered a visarga at the end of the first word. Usually it is derived from a sakAra at the end of the word.</p>";
    echo " <p class = hn >आपने प्रथम शब्द के अन्त में विसर्ग का प्रयोग किया है । सामान्यतः यह सकारान्त शब्द से उद्भव होता है ।</p>";
}
if (preg_match('/[s]$/',$first) && $start===1  && $pada ==="pada" && $eg !==1 && $tasmat !== 1)
{
     $text = one(array($first),array(substr($first,0,strlen($first)-1)."r@"),0);
     echo "<p class = sa >By sasajuSo ruH (8.2.66) :</p>"; 
      echo "<p class = sa >ससजुषो रुः (८.२.६६) :</p>";$r1= 1;
     display(0);
}
elseif ($start>1 && $r1!==0) { $r1 = 1; } else {$r1=0; }
if (arr($text,'/[s]$/') && $start===1 && $eg !==1 && $tasmat !== 1)
{
    $text = last(array("s"),array("r@"),0);
     echo "<p class = sa >By sasajuSo ruH (8.2.66) :</p>"; 
     echo "<p class = sa >ससजुषो रुः (८.२.६६) :</p>";$r2 = 1;
     display(0);
}
elseif ($start>1 && $r2!==0) 
    {
    $r2 = 1; 
    } 
else 
    {
    $r2=0; 
    }
if (arr($text,'/[H]$/') && $start===1  && $pada ==="pada" && $eg !==1 && $tasmat !== 1)
{
     $text = last(array("H"),array("r@"),0);
      echo " <p class = hn >You have entered a visarga at the end of the second word. Usually it is derived from a sakAra at the end of the word.</p>";
    echo " <p class = hn >आपने द्वितीय शब्द के अन्त में विसर्ग का प्रयोग किया है । सामान्यतः यह सकारान्त शब्द से उद्भव होता है ।</p>";
     echo "<p class = sa >By sasajuSo ruH (8.2.66) :</p>"; 
      echo "<p class = sa >ससजुषो रुः (८.२.६६) :</p>";$r1= 1;
     display(0);
}
    /* ahan(8.2.68) and ro'supi (8.2.69) and rUparAtrirathantareSu vAcyam (vA 4847) */ 
if (sub(array("ahan"),blank(0),blank(0),0) && $first === "ahan" && !(in_array($so,$sup)) && $pada==="pada")
{ 
    if ((strpos($so,"rUp")===0)||(strpos($so,"rAtr")===0)||(strpos($so,"raTantar")===0))
    {
    $text = one(array("ahan"),array("ahar@"),0); 
    echo "<p class = sa >By ahan (8.2.68) and rUparAtrirathantareSu vAcyam (vA 4847).</p>";
     echo "<p class = sa >अहन्‌ (८.२.६८) तथा रूपरात्रिरथन्तरेषु वाच्यम्‌ (वा ४८४७) ।</p>";
     display(0);
    }
    else 
    {
    $text = one(array("ahan"),array("ahar"),0);
        echo "<p class = sa >ro'supi (8.2.69) :</p>";
     echo "<p class = sa >रोऽसुपि (८.२.६९) :</p>";
     display(0);
    }
}
if (sub(array("ahan"),blank(0),blank(0),0) && (in_array($so,$sup)) && $pada === "pada")
{
    $text = one(array("ahan"),array("ahar@"),0);
    echo "<p class = sa >By ahan (8.2.68) :</p>";
     echo "<p class = sa >अहन्‌ (८.२.६८) :</p>";
   display(0); $r3 = 1;
} else { $r3 = 0; }
/* samaH suTi (8.3.5) */ // have used @ as mark of anunAsika u of ru. 
if (sub(array("sam"),array("s"),array("k"),0))
{
$text = three(array("sam"),array("s"),array("k"),array("saMr@"),array("s"),array("k"),0);
$text = one(array("Mr@"),array("!r@"),1);
echo "<p class = sa >By samaH suTi (8.3.5), atrAnunAsikaH pUrvasya tu vA (8.3.2) and anunAsikAtparo'nusvAraH (8.3.4) :</p>";
echo "<p class = sa >समः सुटि (८.३.५), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) तथा अनुनासिकात्परोऽनुस्वारः (८.३.४) :</p>";
display(0); $r4 = 1;
} else { $r4 = 0; }
/* khyAYAdeze na (vA 1591) */
if (sub(array("pum"),array("Ky"),blank(0),0))
{
echo "<p class = sa >By khyAYAdeze na (vA 1591) :</p>";
echo "<p class = sa >ख्याञादेशे न (वा १५९१) :</p>";
display(0); $pum = 1;
} else { $pum = 0; }
/* pumaH khayyampare (8.3.6) */
$am = array("a","A","i","I","u","U","f","F","x","X","e","o","E","O","h","y","v","r","l","Y","m","N","R","n");
if(sub(array("pum"),prat('Ky'),$am,0) && $pum === 0)
{
$text = three(array("pum"),prat('Ky'),$am,array("puMr@"),prat('Ky'),$am,0);
$text = one(array("Mr@"),array("!r@"),1);
echo "<p class = sa >By pumaH khayyampare (8.3.6), atrAnunAsikaH pUrvasya tu vA (8.3.2) and anunAsikAtparo'nusvAraH (8.3.4) :</p>";
echo "<p class = sa >पुमः खय्यम्परे (८.३.६), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) तथा अनुनासिकात्परोऽनुस्वारः (८.३.४) :</p>";
display(0); $r5 = 1;
} else { $r5 = 0; }
/* nazChavyaprazAn (8.3.7) */
if (sub(array("n"),prat('Cv'),$am,0) && arr($text,'/[n][+]['.pc('Cv').']/') && $pada ==="pada")
{
$text = three(array("n"),prat('Cv'),$am,array("Mr@"),prat('Cv'),$am,0);
$text = one(array("praSAMr@"),array("praSAn"),0);
$text = one(array("Mr@"),array("!r@"),1);
echo "<p class = sa >By nazChavyaprazAn (8.3.7), atrAnunAsikaH pUrvasya tu vA (8.3.2) and anunAsikAtparo'nusvAraH (8.3.4) :</p>";
echo "<p class = sa >नश्छव्यप्रशान्‌ (८.३.७), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) तथा अनुनासिकात्परोऽनुस्वारः (८.३.४)  :</p>";
display(0); $r6 = 1;
} else { $r6 = 0; }
/* nRUnpe (8.3.10) */
if (sub(array("nFn"),array("p"),blank(0),0)  && $pada ==="pada")
{
$text = two(array("nFn"),array("p"),array("nFMr@"),array("p"),0);
$text = one(array("Mr@"),array("!r@"),1);
echo "<p class = sa >By nRUnpe (8.3.10), atrAnunAsikaH pUrvasya tu vA (8.3.2) and anunAsikAtparo'nusvAraH (8.3.4) </p>";
echo "<p class = sa >नॄन्पे (८.३.१०), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) तथा अनुनासिकात्परोऽनुस्वारः (८.३.४) : </p>";
display(0); $r7 = 1;
} else { $r7 =0;}
/* svatavAn pAyau (8.3.11) */
if (sub(array("svatavAn"),array("pAyu"),blank(0),0)  && $pada ==="pada")
{
$text = two(array("svatavAn"),array("pAyu"),array("svatavA! "),array("pAyu"),0);
echo "<p class = sa >By svatavAn pAyau (8.3.11), atrAnunAsikaH pUrvasya tu vA (8.3.2)</p>";
echo "<p class = sa >स्वतवान्पायौ (८.३.११), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) : </p>";
display(0); $r8 = 1;
} else { $r8 =0;}
/* kAnAmreDite (8.3.12) */ 
if (sub(array("kAn"),array("kAn"),blank(0),0))
{
$text = two(array("kAn"),array("kAn"),array("kAMr@"),array("kAn"),0);
$text = one(array("Mr@"),array("!r@"),1);
echo "<p class = sa >By kAnAmreDite (8.3.12), atrAnunAsikaH pUrvasya tu vA (8.3.2) and anunAsikAtparo'nusvAraH (8.3.4) :</p>";
echo "<p class = sa >कानाम्रेडिते (८.३.१२), अत्रानुनासिकः पूर्वस्य तु वा (८.३.२) तथा अनुनासिकात्परोऽनुस्वारः (८.३.४)  :</p>";
display(0); $r8 = 1;
} else { $r8 =0; }
/* ato roraplutAdaplute (6.1.113) */
if (sub(array("ar@"),array("a"),blank(0),0))
{
    $text = two(array("ar@"),array("a"),array("au"),array("a"),0);
    echo "<p class = sa >By ato roraplutAdaplute (6.1.113) :</p>";
    echo "<p class = sa >अतो रोरप्लुतादप्लुते (६.१.११३) :</p>";
    display (0); $ato = 1;
} else {$ato = 0;}
/* hazi ca (6.1.114) */
if (sub(array("a"),array("r@"),prat('hS'),0))
{
    $text = three(array("a"),array("r@"),prat('hS'),array("a"),array("u"),prat('hS'),0);
    echo "<p class = sa >By hazi ca (6.1.114) :</p>";
    echo "<p class = sa >हशि च (६.१.११४) :</p>";
    display (0); $hazi = 1;
} else { $hazi = 0; } 
/* ekaH pUrvaparayoH (6.1.84) */ // This is the adhikArasUtra. No vidhi mentioned.
// The following vArtikas are exception to AdguNaH. Otherwise after joining, it will be difficult to identify. So coded here.
/* akSAdUhinyAmupasaMkhyAnam (vA 3604) */
/* svAdireriNoH (vA 3606) */
/*prAdUhoDhoDyeSaiSyeSu (vA 3605) */
/* Rte ca tRtIyAsamAse (vA 3607) */
/* pravatsatarakambalavasanadazArNAnAmRNe (vA 3608-9) */
$v1 = array('akza','sva','pra','pra','pra','pra','suKa','pra','vatsatara','kambala','vasana','daSa','fRa','sva');
$v2 = array('Uhin','ir','Uh','UQ','ez','ezy','ft','fR','fR','fR','fR','fR','fR','Ir');
$v3 = array('akz','sv','pr','pr','pr','pr','suK','pr','vatsatar','kambal','vasan','daS','fR','sv');
$v4 = array('OhiR','Er','Oh','OQ','Ez','Ezy','Art','ArR','ArR','ArR','ArR','ArR','ArR','Er');
if (sub($v1,$v2,blank(0),0) && $pada === "pada")
{
$text = two($v1,$v2,$v3,$v4,0);
echo "<p class = sa >Applying the following vArtikas : akSAdUhinyAmupasaMkhyAnam (vA 3604), svAdireriNoH (vA 3606), prAdUhoDhoDyeSaiSyeSu (vA 3605), Rte ca tRtIyAsamAse (vA 3607), pravatsatarakambalavasanadazArNAnAmRNe (vA 3608-9)</p>";
echo "<p class = sa >अक्षादूहिन्यामुपसंख्यानम्‌ (वा ३६०४), स्वादेरेरिणोः (वा ३६०६), प्रादूहोढोढ्येषैष्येषु (वा ३६०५), ऋते च तृतीयासमासे (वा ३६०७), प्रवत्सतरकम्बलवसनदशार्णानामृणे (वा ३६०८-०९)</p>";
display(0);
}
/* etyedhatyuThsu (6.1.89) */ 
if (sub(array("a","A"),array("eti","ezi","emi","etu","Et","EtAm","EH","Es","Etam","Eta","Eva","Ema","ezyati","Ezyati","etA","eD","ED","Uh"),blank(0),0))
{
    $text = two (array("a","A"),array("eti","ezi","emi","etu","Et","EtAm","EH","Es","Etam","Eta","Eva","Ema","ezyati","Ezyati","etA","eD","ED","Uh"),blank(2),array("Eti","Ezi","Emi","Etu","Et","EtAm","EH","Es","Etam","Eta","Eva","Ema","Ezyati","Ezyati","EtA","ED","ED","Oh"),0);
    echo "<p class = sa >By etyedhatyuThsu (6.1.89) :</p>";
    echo "<p class = sa >एत्येधत्यूठ्सु (६.१.८९) :</p>";
    display(0);
}
/* AdguNaH (6.1.87) */
$forguna = array("i","I","u","U");
$rep = array("e","e","o","o");
if (sub($aa,$forguna,blank(0),0))
{
$text = two($aa,$forguna,blank(2),$rep,0);
echo "<p class = sa >By AdguNaH (6.1.87) :</p>";
echo "<p class = sa >आद्गुणः (६.१.८७) :</p>";
display(0);
}
/* uraNraparaH (1.1.51) */ 
$forguna = array("f","F","x","X");
$rep = array("ar","ar","al","al");
if (sub($aa,$forguna,blank(0),0))
{
$text = two($aa,$forguna,blank(2),$rep,0);
echo "<p class = sa >By AdguNaH (6.1.87) and uraNraparaH (1.1.51) :</p>";
echo "<p class = sa >आद्गुणः (६.१.८७) तथा उरण्रपरः (१.१.५१) :</p>";
display(0);
}
/* eGi pararUpam (6.1.94) */ // Added it here because it is exception to vRddhireci.
for($i=0;$i<count($akarantaupasarga);$i++)
{
    $a_upa_without_a[$i] = substr($akarantaupasarga[$i],0,count(str_split($akarantaupasarga[$i]))-1); 
}
if (sub($akarantaupasarga,prat('eN'),blank(0),0) && arr($text,'/[I][y]/') && in_array($first,$akarantaupasarga))
{
$text = two($akarantaupasarga,prat('eN'),$a_upa_without_a,prat('eN'),1);
echo "<p class = sa >By eGi pararUpam (6.1.94) and anuvRtti of vA supi :</p>";
echo "<p class = sa >एङि पररूपम्‌ (६.१.९४) तथा वा सुपि की अनुवृत्ति :</p>";
display(0);
}
elseif (sub($akarantaupasarga,prat('eN'),blank(0),0) && in_array($first,$akarantaupasarga))
{
$text = two($akarantaupasarga,prat('eN'),$a_upa_without_a,prat('eN'),0);
echo "<p class = sa >By eGi pararUpam (6.1.94) :</p>";
echo "<p class = sa >एङि पररूपम्‌ (६.१.९४) :</p>";
display(0);
}
/* eve cAniyoge (vA 3631) */
$eva = array("eva");
if (sub($aa,$eva,blank(0),0))
{
$text = two($aa,$eva,blank(2),$eva,1);
echo "<p class = sa >By eve cAniyoge (vA 3631) :</p>
    <p class = hn >N.B. that the optionality applies only in case the eva is used for avadhAraNa.</p>" ;
echo "<p class = sa >एवे चावधारणे (वा ३६३१) :</p>
    <p class = hn >जब 'एव' अवधारण के अर्थ में प्रयुक्त हुआ हो, तभी यह नियम लागू होता है ।</p>" ;
display(0);
}
/* vA supyapizaleH (6.1.92) */ // Not possible to know what is nAmadhAtu and what is not. Therefore added as comments. Not coded.
/* aco'ntyAdi Ti (1.1.64) */ // a saJjJAsUtra. No vidhi mentioned.
/* otvoShThayoH samAse vA (vA 3634) */
$otu = array("otu","ozW");
if (sub($aa,$otu,blank(0),0))
{
$text = two($aa,$otu,blank(2),$otu,1);
echo "<p class = sa >By otvoShThayoH samAse vA (vA 3634) :</p>
    <p class = hn >If what you entered is a samAsa, it will be optionally converted. Otherwise ignore the pararUpa form.</p>";
echo "<p class = sa >ओत्वोष्ठ्योः समासे वा (वा ३६३४) :</p>
    <p class = hn >यदि समास है तभी यह नियम लागू होगा । अन्यथा पररूप वाला रूप नहीं बनेगा ।</p>";
display(0);
}
/* nAmreDitasyAntasya tu vA (6.1.99), tasya paramAmreDitam (8.1.2) */
for($i=0;$i<count($text);$i++)
{
    $tttt = explode("at",$text[$i]);
    if (count($tttt) > 1 )
    {
    if ($tttt[0] === $tttt[1])
    {
        //echo "<p class = hn >Your data matches criteria for AmreDita.</p>";
        $amredita = 1;
        break;
    }
    else
    {
        $amredita = 0;
    }
    }
    else
    {
        $amredita = 0;
    }
}
if (($amredita === 1) && $start === 1)
{
$at = array("at");
$iti = array("iti");
$text = two($at,$iti,array("a"),$iti,1);
echo "<p class = sa >nAmreDitasyAntasya tu vA (6.1.99), tasya paramAmreDitam (8.1.2) :</p>
    <p class = hn >When the 'at' happens to be at the end of an onaematopic word and it is followed by 'iti', its 'Ti' is elided. This rule doesn't apply on single vowel words like 'zrat'.</p>";
echo "<p class = sa >नाम्रेडितस्यान्तस्य तु वा (६.१.९९), तस्य परमाम्रेडितम्‌ (८.१.२) :</p>
    <p class = hn >आम्रेडित में यह नियम लागू वैभाषिक तौर से होता है, और अन्तिम तकार का होता है । एकाच्‌ शब्दों में, जैसे कि श्रत्‌, यह लागू नहीं होता ।</p>";
display(0);   
}
/* avyaktAnukaraNasyAta itau (6.1.98) */
$at = array("at");
$iti = array("iti");
$ff = preg_split('/[aAiiuUfFxXeEoO]/',$first);
if (sub($at,$iti,blank(0),0) && $amredita === 0 && $start ===1 && count($ff)>2)
{
$text = two($at,$iti,blank(1),$iti,0);
echo "<p class = sa >By avyaktAnukaraNasyAta itau (6.1.98) :</p>
    <p class = hn > When the 'at' happens to be at the end of an onaematopic word and it is followed by 'iti', its 'Ti' is elided. This rule doesn't apply on single vowel words like 'zrat'.</p>";
echo "<p class = sa >अव्यक्तानुकरणस्यात इतौ (६.१.९८) :</p>
    <p class = hn >अव्यक्तानुकरण में प्रयुक्त हुए शब्द के अत्‌ के बाद में यदि इति हो तो यह नियम लागू होता है । एकाच्‌ शब्दों में, जैसे कि श्रत्‌, यह लागू नहीं होता ।</p>";
display(0);
}
/* vRddhireci (6.1.88) */
$vrrdhi = array("E","O","E","O");
if (sub($aa,prat('ec'),blank(0),0))
{
$text = two($aa,prat('ec'),blank(2),$vrrdhi,0);
echo "<p class = sa >By vRddhireci (6.1.88) :</p>";
echo "<p class = sa >वृद्धिरेचि (६.१.८८) :</p>";
display(0);
}
/* udaH sthAstambhvoH pUrvasya (8.1.61) */
if(sub(array("ud","ut"),array("sTA","stam"),blank(0),0))
{
$text = two(array("ud","ut"),array('sTA','stam'),array("ud","ut"),array('TTA','Ttam'),0);
echo "<p class = sa >By udaH sthAstambhvoH pUrvasya (8.1.61) :</p>";
echo "<p class = sa >उदः स्थास्तम्भ्वोः पूर्वस्य (८.१.६१) :</p>";
display(0);
}
/* saMhitAyAm (6.1.72) */ // This is adhikArasUtra. Nothing to code here.
/* Che ca (6.1.73) */
$che = array("a","i","u","f","x");
$hrasvata = array("at","it","ut","ft","xt");
if (sub($hrasva,array("C"),blank(0),0))
{
$text = two($che,array("C"),$hrasvata,array("C"),0);
echo "<p class = sa >By Che ca (6.1.73) :</p>";
echo "<p class = sa >छे च (६.१.७३) :</p>";
display(0);
}
/* AGmAGozca (6.1.74) */
if (($first === "A" || $first === "mA") && $start===1)
{
$text = two(array("A"),array("C"),array("At"),array("C"),0);
echo "<p class = sa >By AGmAGozca (6.1.74) :</p>";
echo "<p class = sa >आङ्माङोश्च (६.१.७४) :</p>";
display(0);
}
/* dIrghAt (6.1.75) and padAntAdvA (6.1.76) */
$dirghata = array("At","It","Ut","Ft","Xt","et","Et","ot","Ot");
if (sub($dirgha,array("C"),blank(0),0) && $pada === "pratyaya" && $start === 1)
{
$text = two($dirgha,array("C"),$dirghata,array("C"),0);
echo "<p class = sa >By dIrghAt (6.1.75) padAntAdvA (6.1.76) :</p>
    <p class = hn >N.B.: The 'tuk' Agama is optional in case the preceding dIrgha vowel is at the padAnta. Otherwise, it is mandatory to add.</p>";
echo "<p class = sa >दीर्घात्‌ (६.१.७५) तथा पदान्ताद्वा (६.१.७६) :</p>
    <p class = hn >यदी दीर्घ स्वर पदान्त में हो तब तुक्‌ आगम लगाना पाक्षिक है । अन्यथा यह आवश्यक है ।</p>";
display(0);
}
if (sub($dirgha,array("C"),blank(0),0) && $pada === "pada" && $start ===1)
{
$text = two($dirgha,array("C"),$dirghata,array("C"),1);
echo "<p class = sa >By dIrghAt (6.1.75) padAntAdvA (6.1.76) :</p>
    <p class = hn >N.B.: The 'tuk' Agama is optional in case the preceding dIrgha vowel is at the padAnta. Otherwise, it is mandatory to add.</p>";
echo "<p class = sa >दीर्घात्‌ (६.१.७५) तथा पदान्ताद्वा (६.१.७६) :</p>
    <p class = hn >यदी दीर्घ स्वर पदान्त में हो तब तुक्‌ आगम लगाना पाक्षिक है । अन्यथा यह आवश्यक है ।</p>";
display(0);
}
/* yasmAtpratyayavidhistadAdi pratyaye'Ggam (2.4.13) */
// Pending to code.
/* creating a + elision for two ++ simultaneously. */
$text = one(array("++"),array("+"),0);

// looping till all the applicable sUtras of sapAdasaptAdhyAyI are exhausted. i.e. the original and the output are the same.
$start++;
}
while ($text !== $original);


/* tripAdI functions */

/* na lopaH prAtipadikAntasya (8.2.7) */
if (arr($text,'/[n][+]/') && in_array($so,$sup))
{
    $text = two(array("n"),array("+"),array(""),array("+"),0);
    echo "<p class = sa >By na lopaH prAtipadikAntasya (8.2.7) :</p>";
    echo "<p class = sa >न लोपः प्रातिपदिकान्तस्य (८.२.७) :</p>";
    display(0);        
}
/* vrazcabhrasjamRjayajarAjabhrAjacChazAM ca (8.2.35) */
$vrasca = array("vfSc","Bfsj","sfj","mfj","yaj","rAj","BrAj",);
$vrashca = array("vfSz","Bfsz","sfz","mfz","yaz","rAz","BrAz");
if ((sub($vrasca,blank(0),blank(0),0)) && $pada ==="pada" )
{
    if (sub($vrasca,$hl,blank(0),0))
    {
    $text = two($vrasca,prat('Jl'),$vrashca,prat("Jl"),0);
    }
    else 
    {
    $text = one($vrasca,$vrashca,0);    
    }
    echo "<p class = sa >By vrazcabhrasjasRjamRjayajarAjabhrAjacChazAM ShaH (8.2.35) :</p>";
    echo "<p class = sa >व्रश्चभ्रस्जसृजमृजयजराजभ्राजच्छशां षः (८.२.३५) :</p>";
    display(0); $vras1 = 1;
} else { $vras1 = 0; }
if (arr($text,'/[CS]$/'))
{
    $text = last(array("C","S"),array("z","z"),0);
    echo "<p class = sa >By vrazcabhrasjasRjamRjayajarAjabhrAjacChazAM ShaH (8.2.35) :</p>";
    echo "<p class = sa >व्रश्चभ्रस्जसृजमृजयजराजभ्राजच्छशां षः (८.२.३५) :</p>";
    display(0); $vras3 = 1;
} else { $vras3 =0; }
if (arr($text,'/[CS][+]/') && $pada === "pada")
{
    $text = two(array("C","S"),array("+"),array("z","z"),array("+"),0);
    echo "<p class = sa >By vrazcabhrasjasRjamRjayajarAjabhrAjacChazAM ShaH (8.2.35) :</p>";
    echo "<p class = sa >व्रश्चभ्रस्जसृजमृजयजराजभ्राजच्छशां षः (८.२.३५) :</p>";
    display(0); $vras4 = 1;
} else { $vras4 = 0; } 
/* nimittApAye naimittikasyApyapAyaH (paribhASA) */ 
if (($vras1===1 && sub(array("vfSz"),blank(0),blank(0),0)) || (($vras3 ===1 || $vras4 ===1) && sub(array("cz"),blank(0),blank(0),0)))
{
    $text = one(array("vfSz"),array("vfsz"),0);
    $text = one(array("cz"),array("z"),0);
    echo "<p class = sa >By nimittApAye naimittikasyApyapAyaH (paribhASA) :</p>";
    echo "<p class = sa >निमित्तापाये नैमित्तिकस्याप्यपायः (परिभाषा) :</p>";
    display(0);
} 
/* skoH saMyogAdyorante ca (8.2.29) */
if ((sub(array("s","k"),$hl,prat("Jl"),0) || arr($text,'/[sk]['.flat($hl).']$/'))  && $pada === "pada")
{
    $text = three(array("s","k"),$hl,prat("Jl"),array("",""),$hl,prat("Jl"),0);
    $text = three($ac,array("s","k"),$hl,$ac,array("",""),$hl,0);
    echo "<p class = sa >By skoH saMyogAdyorante ca (8.2.29) :</p>";
    echo "<p class = sa >स्कोः संयोगाद्योरन्ते च (८.२.२९) :</p>";
    display(0);
}
/* coH kuH (8.2.30) */
$cu = array("c","C","j","J","Y");
$ku = array("k","K","g","G","N");
$noco = array("ac","ic","ec","Ec");
if ((arr($text,'/['.flat($cu).'][+]['.pc('Jl').']/')) && !in_array($first,$noco))
{
$text = three($cu,array("+"),prat('Jl'),$ku,array("+"),prat('Jl'),0); 
echo "<p class = sa >By coH kuH (8.2.30) :</p>";
echo "<p class = sa >चोः कुः (८.२.३०) :</p>";
display(0);
}
if (!in_array($second,$noco) && arr($text,'/['.flat($cu).']$/'))
{
    $text = last($cu,$ku,0);
    echo "<p class = sa >By coH kuH (8.2.30) :</p>";
    echo "<p class = sa >चोः कुः (८.२.३०) :</p>";
    display(0);   
}
/* vA druhamuhaSNuhaSNihAm (8.2.34) */
$druh = array("druh","muh","snuh","snih");
if (sub($druh,blank(0),blank(0),0) && ($second = "" || arr($text,'/[+]['.pc("Jl").']/')) )
{ 
    $text = one(array("druh","muh","snuh","snih"),array("druG","muG","snuG","sniG"),1);
    echo "<p class = sa >By vA druhamuhaSNuhaSNihAm (8.2.34) :</p>";
    echo "<p class = sa >वा द्रुहमुहष्णुहष्णिहाम्‌ (८.२.३४) :</p>"; 
    display(0);
}
/* dAderdhAtorghaH (8.2.33) */
$dade = array("dah","dAh","dih","duh","dfh","drAh",);
if (sub($dade,blank(0),blank(0),0) && ($second = "" || arr($text,'/[+]['.pc("Jl").']/')) )
{
    $text = one(array("dah","dAh","dih","duh","dfh","drAh","druh"),array("daG","dAG","diG","duG","dfG","drAG","druG"),0);
    echo "<p class = sa >By dAderdhAtorghaH (8.2.33) :</p>";
    echo "<p class = sa >दादेर्धातोर्घः (८.२.३३) :</p>";
    display(0); $hodha1 = 1;
} else { $hodha1 = 0; } 
/* naho dhaH (8.2.35) */
if (sub(array("nah"),blank(0),blank(0),2) && ($second = "" || arr($text,'/[+]['.pc("Jl").']/')) )
{
    $text = one(array("nah",),array("naD"),0);
    echo "<p class = sa >By naho dhaH (8.2.35) :</p>";
    echo "<p class = sa >नहो धः (८.२.३५) :</p>";
    display(0); $hodha2 = 1; 
} else { $hodha2 = 0; } 
/* AhasthaH (8.2.36) */
if (in_array($first,array("Ah")) && (arr($text,'/[+]['.pc("Jl").']/')) )
{
    $text = one(array("Ah",),array("AT"),0);
    echo "<p class = sa >By AhasthaH (8.2.36) :</p>";
    echo "<p class = sa >आहस्थः (८.२.३६) :</p>";
    display(0); $hodha3=1;
} else { $hodha3 = 0; } 
/* ho DhaH (8.2.32) */ 
if (arr($text,'/[h][+]/') && sub(array("h"),prat("Jl"),blank(0),0) && $pada ==="pada" && $hodha1===0 && $hodha2 === 0 && $hodha3 === 0)
{
    $text = two(array("h"),prat('Jl'),array("Q"),prat('Jl'),0);
    echo "<p class = sa >ho DhaH (8.2.32)  :</p>";
    echo "<p class = sa >हो ढः (८.२.३२)  :</p>";
    display(0);    
}
if (arr($text,'/[h]$/')  && $hodha1===0 && $hodha2 === 0 && $hodha3 === 0)
{
    $text = last(array("h"),array("Q"),0);
    echo "<p class = sa >ho DhaH (8.2.32)  :</p>";
    echo "<p class = sa >हो ढः (८.२.३२)  :</p>";
    display(0);    
}
/* ekAco bazo bhaS jhaSantasya sdhvoH (8.2.37) */  // Not good code. Think hard.
$ekaco = array("gaD","gaB","gaQ","gAQ","gAD","gAQ","guD","guQ","gUQ","gfD","gfQ","graB","graQ","griQ","glaQ","qaQ","qiQ","quQ","daG","daG","daG","daG","diG","duG","duG","dfG","dfG","dfG","drAG","drAG","druG","druh","baD","baQ","bAQ","bAD","bAQ","bIB","buD","bfQ","beQ","braQ","druQ");
$ekaco1 = array("GaD","GaB","GaQ","GAQ","GAD","GAQ","GuD","GuQ","GUQ","GfD","GfQ","GraB","GraQ","GriQ","GlaQ","QaQ","QiQ","QuQ","DaG","DaG","DaG","DaG","DiG","DuG","DuG","DfG","DfG","DfG","DrAG","DrAG","DruG","DruQ","BaD","BaQ","BAQ","BAD","BAQ","BIB","BuD","BfQ","BeQ","BraQ","DruQ");
if (sub($ekaco,blank(0),blank(0),0) && ($pada === "pratyaya" && arr($text,'/[+][sd]')))
{
 $text = one($ekaco,$ekaco1,0);
 echo "<p class = sa >By ekAco bazo bhaS jhaSantasya sdhvoH (8.2.37) :</p>";
    echo "<p class = sa >एकाचो बशो भष्‌ झषन्तस्य स्ध्वोः (८.२.३७):</p>";
    display(0);  
}
/* jhalAM jazo'nte (8.2.39) */ 
if (arr($text,'/['.pc('Jl').']$/') && $sambuddhi === 0)
{
    if ($r2 ===1) 
         {
            echo "<p class = sa >jhalAM jazo'nte is barred by sasajuSo ruH for second word. <hr>"; echo "<p class = sa >द्वितीय पद के लिए ससजुषो रुः से झलां जशोऽन्ते बाधित हुआ है । <hr>";          
         }    
    else 
        {
            $text = last(prat('Jl'),savarna(prat('Jl'),prat('jS')),0);
            echo "<p class = sa >By jhalAM jazo'nte (8.2.39), The padAnta is 'jhal' is replaced by 'jaz' :</p>";
            echo "<p class = sa >झलां जशोऽन्ते (८.२.३९) :</p>";
            display(0);
        }
}
if (arr($text,'/['.pc('Jl').'][+]/') && $pada === "pada" && $sambuddhi === 0 )
{ 
    
    if ($r1 === 1 ) {echo "<p class = sa >jhalAM jazo'nte is barred by sasajuSo ruH for first word. <hr>"; echo "<p class = sa >प्रथम पद के लिए ससजुषो रुः से झलां जशोऽन्ते बाधित हुआ है । <hr>";}
    else {$text = two(prat('Jl'),array("+"),savarna(prat('Jl'),prat('jS')),array("+"),0);
    echo "<p class = sa >By jhalAM jazo'nte (8.2.39), The padAnta is 'jhal' is replaced by 'jaz' :</p>";
        echo "<p class = sa >झलां जशोऽन्ते (८.२.३९) :</p>";
            display(0);    }
} 
/* rvorupadhAyA dIrgha ikaH (8.2.76) */
if ($dhatu===1 && ((arr($text,'/[iIuUfFxX][rv][+]‌(^['.pc('hl').'])/') && $pada==="pada" )||arr($text,'/[iIuUfFxX][rv]$/')||(arr($text,'/[iIuUfFxX][rv][+]$/')) ))
{
    $text = three(array("i","I","u","U","f","F","x","X",),array("r","v"),array("+"),array("I","I","U","U","F","F","F","F",),array("r","v"),array("+"),0);
    echo "<p class = sa >By rvorupadhAyA dIrgha ikaH (8.2.76) :</p>";
    echo "<p class = sa >र्वोरुपधाया दीर्घ इकः (८.२.७६) :</p>";
    display(0); $r4 = 1;
}
/* hali ca (8.2.77) */
if ($dhatu===1 && arr($text,'/[iIuUfFxX][rv][+]['.pc('hl').']/'))
{
    $text = three(array("i","I","u","U","f","F","x","X",),array("r+","v+"),$hl,array("I","I","U","U","F","F","F","F",),array("r+","v+"),$hl,0);
    echo "<p class = sa >By hali ca (8.2.77) :</p>";
    echo "<p class = sa >हलि च (८.२.७७) :</p>";
    display(0); $r4 = 1;
}
/* bhobhagoaghoapUrvasya yo'zi (8.3.17) : */
$ash = array("a","A","i","I","u","U","f","F","x","X","e","o","E","O","h","y","v","r","l","Y","m","N","R","n","J","B","G","Q","D","j","b","g","q","d");
if (sub(array("Bo","Bago","aGo","a","A"),array("r@"),$ash,0)) 
{
    $text = three(array("Bo","Bago","aGo","a","A"),array("r@"),$ash,array("Bo","Bago","aGo","a","A"),array("y"),$ash,0);
    echo "<p class = sa >By bhobhagoaghoapUrvasya yo'zi (8.3.17):</p>";
     echo "<p class = sa >भोभगोअघोअपूर्वस्य योऽशि (८.३.१७) :</p>";
    $bho = 1;
    display (0);
} else { $bho =0; }
/* vyorlaghuprayatnataraH zAkaTAyanasya (8.3.18) */
// This is regarding pronounciation.

// Patch to convert the rutva before vowels and hash to repha.
$text = two(array("r@"),$ac,array("r"),$ac,0);
$text = two(array("r@"),prat('hS'),array("r"),prat('hS'),0);
/* kharavasAnayorvisarjanIyaH (8.3.15) */
if (arr($text,'/[+]['.pc('Kr').']/') && sub(array("r"), array("@"),prat('Kr'),0) && $pada === "pada")
{
 $text = two(array("r@"),prat("Kr"),array("H"),prat("Kr"),0);
 echo "<p class = sa >By kharavasAnayorvisarjanIyaH (8.3.15) :</p>";
 echo "<p class = sa >खरवसानयोर्विसर्जनीयः (८.३.१५) :</p>";
 display(0);
}
if ( arr($text,'/[@r]$/')||arr($text,'/[r][+]$/'))
{
 $text = last(array("r@","r"),array("H","H"),0);
 if (arr($text,'/[r][+]$/'))
 {
     $text = one(array("+"),array(""),0);
 }
 $text = last(array("r"),array("H"),0);
 echo "<p class = sa >By kharavasAnayorvisarjanIyaH (8.3.15) :</p>";
 echo "<p class = sa >खरवसानयोर्विसर्जनीयः (८.३.१५) :</p>";
 display(0);
}
/* Dho Dhe lopaH (8.3.13) */
if (sub(array("Q"),array("Q"),blank(0),0))
{
    $text = two(array('Q'),array('Q'),array(''),array('#Q'),0); 
    echo "<p class = sa >By Dho Dhe lopaH (8.3.13) :</p>";
    echo "<p class = sa >ढो ढे लोपः (८.३.१३) :</p>";
    display(0); $dho = 1;
} else { $dho = 0; }
/* ro ri (8.3.14) */
if (sub(array("r"),array("r"),blank(0),0))
{
    $text = two(array('r'),array('r'),array(''),array('#r'),0); 
    $ro = 1;
    echo "<p class = sa >By ro ri (8.3.14) :</p>";
    echo "<p class = sa >रो रि (८.३.१४) :</p>";
    display(0);
} else { $ro = 0; }
/* Dhralope pUrvasya dIrgho'NaH (6.3.111) */
$ana = array("a","A","i","I","u","U","f","F","x","X");
$anna = array("A","A","I","I","U","U","F","F","X","X");
if (($ro ===1 || $dho===1) && sub($ana,array('#r',"#Q"),blank(0),0))
{
$text = two($ana,array('#r','#Q'),$anna,array(' r',' Q'),0);
echo "<p class = sa >By Dhralope pUrvasya dIrgho'NaH (6.3.111) :</p>";
echo "<p class = sa >ढ्रलोपे पूर्वस्य दीर्घोऽणः (६.३.१११) :</p>";
display(0);
}
/* lopaH zAkalyasya (8.3.19) and vyorlaghuprayatnataraH zAkaTAyanasya (8.3.18) */ 
$aa = array("a","A");$yv = array("y+","v+"); $space=array(" "," ");
if (sub($aa,$yv,$ac,0) && (arr($text,'/['.pc('ec').'][+]['.flat($ac).']/') || $bho === 1) && $pada === "pada")
{
echo "<p class = sa >By lopaH zAkalyasya (8.3.19) and vyorlaghuprayatnataraH zAkaTAyanasya (8.3.18) :</p>";
echo "<p class = sa >लोपः शाकल्यस्य (८.३.१९) तथा व्योर्लघुप्रयत्नः शाकटायनस्य (८.३.१८) :</p>";
$text = three($aa,$yv,$ac,$aa,array(" +"," +"),$ac,1); 
display(0);
}
/* hali sarveSAm (8.3.22) */
if ($bho === 1 && sub(array("y+"),$hl,blank(0),0) && $pada==="pratyaya")
{
    $text = three(array("Bo","Bago","aGo","A"),array("y+"),$hl,array("Bo","Bago","aGo","A"),array("+"),$hl,0);
    echo "<p class = sa >By hali sarveSAm (8.3.22) :</p>";
    echo "<p class = sa >हलि सर्वेषाम्‌ (८.३.२२) :</p>";
    display(0);
}
if ($bho === 1 && sub(array("y+"),$hl,blank(0),0) && (in_array($so,$sup) && $pada==="pada"))
{
    $text = three(array("Bo","Bago","aGo","A"),array("y+"),$hl,array("Bo","Bago","aGo","A"),array("+"),$hl,0);
    echo "<p class = sa >By hali sarveSAm (8.3.22) :</p>";
    echo "<p class = sa >हलि सर्वेषाम्‌ (८.३.२२) :</p>";
    display(0);
}
/* oto gArgyasya (8.3.20) */
if (arr($text,'/[o][y][+]/') && $bho ===1 && $pada === "pada")
{
    $text = one(array("oy+"),array("o +"),0);
    echo "<p class = sa >By oto gArgyasya (8.3.20) :</p>
        <p class = hn >N.B. This rule applies only to the padAnta alaghuprayatna yakAra following 'o' only.</p>";
    echo "<p class = sa >ओतो गार्ग्यस्य (८.३.२०) :</p>
        <p class = hn >यह ओकार के परे आए हुए अलघुप्रयत्न पदान्त यकार को ही लागू होता है ।</p>";
    display(0);
}
/* uJi ca pade (8.3.21) */
if ((sub(array("ay","av"),array("u "),blank(0),0)|| (sub(array("ay","av"),blank(0),blank(0),0) && $second === "u")) && $bho ===1 && $pada === "pada")
{
    $text = two(array("ay","av"),array("u"),array("a","a"),array("u"),0);
    echo "<p class = sa >By uJi ca pade (8.3.21) :</p>";
    echo "<p class = sa >उञि च पदे (८.३.२१) :</p>";
    display(0);
}
/* mo rAji samaH kvau (8.3.25) */
if (sub(array("sam"),array("rA"),blank(0),0))
{
    $text = two(array("sam"),array("rA"),array("sam"),array("rA"),0);
    echo "<p class = sa >By mo rAji samaH kvau (8.3.25) :</p>";
  echo "<p class = sa >मो राजि समः क्वौ (८.३.२५) :</p>";
  display(0); $mo = 1;
} else { $mo = 0; }
/* mo'nusvAraH (8.3.23) */
if (arr($text,'/[m][+]['.pc('hl').']/') && $pada ==="pada" && $mo === 0)
{
$text = two(array('m'),prat('hl'),array('M'),prat('hl'),0);
echo "<p class = sa >By mo'nusvAraH (8.3.23) :</p>
    <p class = hn >N.B.: The conversion to anusvAra occurs only if the m is at the end of a pada. Otherwise this conversion doesn't apply. Ignore all consequentiality in that case.</p>";
echo "<p class = sa >मोऽनुस्वारः (८.३.२३) :</p>
    <p class = hn >यदि मकार पदान्त में है तभी अनुस्वार में बदलता है । अन्यथा नहीं ।</p>";
display(0);
}
/* nazcApadAntasya jhali (8.3.24) */
if (arr($text,'/[mn][+]['.pc('Jl').']/') && $pada === "pratyaya")
{
$text = two(array('m','n'),prat('Jl'),array('M','M'),prat('Jl'),0);
echo "<p class = sa >By nazcApadAntasya jhali (8.3.24) :</p>
    <p class = hn >If n/m is inside a pada, it should be converted to anusvAra. So ignore the case which doesn't apply here.</p>";
echo "<p class = sa >नश्चापदान्तस्य झलि (८.३.२४) :</p>
    <p class = hn >यदि नकार या मकार पदान्त में नहीं है तब भी यह नियम से अनुस्वार होता है ।</p>";
display(0);
}
if(arr($text,'/[mn]['.pc('Jl').']/') )
{
$text = two(array('m','n'),prat('Jl'),array('M','M'),prat('Jl'),2);
echo "<p class = sa >By nazcApadAntasya jhali (8.3.24) :</p>
    <p class = hn >If n/m is inside a pada, it should be converted to anusvAra. So ignore the case which doesn't apply here.</p>";
echo "<p class = sa >नश्चापदान्तस्य झलि (८.३.२४) :</p>
    <p class = hn >यदि नकार या मकार पदान्त में नहीं है तब भी यह नियम से अनुस्वार होता है ।</p>";
display(0);
}
/* he mapare vA (8.3.26) and yavalapare yavalA veti vaktavyam (vA 4902) */
if (sub(array("M"),array("hm","hy","hv","hl"),blank(0),0))
{
$text = two(array("M"),array("hy",),array("!y",),array("hy",),1);
$text = two(array("M"),array("hm",),array("m",),array("hy",),1);
$text = two(array("M"),array("hv",),array("!v",),array("hv",),1);
$text = two(array("M"),array("hl",),array("!l",),array("hl",),1);
echo "<p class = sa >By he mapare vA (8.3.26) and yavalapare yavalA veti vaktavyam (vA 4902) :</p>";
echo "<p class = sa >हे मपरे वा (८.३.२६) तथा यवलपरे यवला वेति वक्तव्यम्‌ (वा ४९०२) :</p>";
display(0);
}
/* napare naH (8.3.27) */
if (sub(array("M"),array("hn"),blank(0),0))
{
$text = two(array("M"),array("hn"),array("n"),array("hn",),1);
echo "<p class = sa >By napare naH (8.3.27) :</p>";
echo "<p class = sa >नपरे नः (८.३.२७) :</p>";
display(0);
}
/* GNoH kukTukzari (8.3.28) */
if (sub(array("N","R"),prat('Sr'),blank(0),0))
{
$text = two(array("N","R"),prat('Sr'),array("Nk","Rw"),prat('Sr'),1);
echo "<p class = sa >By GNoH kukTukzari (8.3.28) :</p>";
echo "<p class = sa >ङ्णोः कुक्टुक्शरि (८.३.२८) :</p>";
display(0);
/* cayo dvitIyAH zari pauSkarasAderiti vAcyam (vA 5023) */
$text = two(array("Nk","Rw"),prat('Sr'),array("NK","RW"),prat('Sr'),1);
echo "<p class = sa >By cayo dvitIyAH zari pauSkarasAderiti vAcyam (vA 5023) :</p>";
echo "<p class = sa >चयोः द्वितीयाः शरि पौष्करसादेरिति वाच्यम्‌ (वा ५०२३) :</p>";
display(0);
}
/* DaH si dhuT (8.3.29) */
if (sub(array("q"),array("s"),blank(0),0))
{
$text = two(array("q"),array("s"),array("q"),array("Ds"),1);
echo "<p class = sa >By DaH si dhuT (8.3.29) :</p>";
echo "<p class = sa >डः सि धुट्‌ (८.३.२९) :</p>";
display(0); $dhut = 1;
} else {$dhut = 0; }
/* nazca (8.3.30) */
if (sub(array("n"),array("s"),blank(0),0))
{
$text = two(array("n"),array("s"),array("n"),array("Ds"),1);
echo "<p class = sa >By nazca (8.3.30) :</p>";
echo "<p class = sa >नश्च (८.३.३०) :</p>";
display(0); $dhut = 1;
} else { $dhut = 0; }
/* zi tuk (8.3.31) */
if (arr($text,'/[n][+][S]/') && $pada === "pada")
{
$text = one(array("n+S"),array("nt+S"),1);    
echo "<p class = sa >By zi tuk (8.3.31) :</p>";
echo "<p class = sa >शि तुक्‌ (८.३.३१) :</p>";
display(0);
}
/* Gamo hrasvAdaci GamuNnityam (8.3.32) */ // Here the Agama has to be affiliated to $ac. Patch is bad.
$nogamo = array("aR","ak","ik","uk","ac","ic","ec","aw","aR","iR","am","aS","al",);
if (arr($text,'/['.flat($hrasva).'][NRn][+]['.flat($ac).']/') && $pada === "pada" && !in_array($second,$nogamo) && !sub(array("pataYjal","sImant"),blank(0),blank(0),0))
{
$text = three($hrasva,array("N","R","n"),$ac,$hrasva,array("NN","RR","nn"),$ac,0);
echo "<p class = sa >By Gamo hrasvAdaci GamuNnityam (8.3.32) :</p>";
echo "<p class = sa >ङमो ह्रस्वादचि ङमुण्नित्यम्‌ (८.३.३२) :</p>";
display(0);
}
/* sampuGkAnAM so vaktavyaH (vA 4892) */
if (sub(array("saM","sa!","puM","pu!","kAM","kA!"),array("H"),blank(0),0))
{
$text = two(array("saM","sa!","puM","pu!","kAM","kA!"),array("H"),array("saM","sa!","puM","pu!","kAM","kA!"),array("s"),0);
echo "<p class = sa >By sampuGkAnAM so vaktavyaH (vA 4892) ";
echo "<p class = sa >सम्पुङ्कानां सो वक्तव्यः (वा ४८९२) ";
display(0);
}
/* samo vA lopameke (bhASya) */
if (sub(array("saMs","sa!s"),array("s"),$hl,0))
{
$text = two(array("saMs","sa!s"),array("s"),array("saM","sa!"),array("s"),1);
echo "<p class = sa >By samo vA lopameke (bhASya) :</p>";
echo "<p class = sa >समो वा लोपमेके (भाष्य) :</p>";
display(0);
}
/* dvistrizcaturiti kRtvo'rthe (8.3.43) */
if (sub(array("dviH","triH","catuH"),$ku,blank(0),0))
{
    $text = two (array("dviH","triH","catuH"),$ku,array("dviz","triz","catuz"),$ku,1);
    echo "<p class = sa >By dvistrizcaturiti kRtvo'rthe (8.3.43) :</p>
        <p class = hn >N.B. This applies only in case of kRtvo'rthe.</p>";
    echo "<p class = sa >द्विस्त्रिश्चतुरिति कृत्वोऽर्थे (८.३.४३):</p>
        <p class = hn >यह नियम सिर्फ कृत्वोऽर्थ में ही लागू होता है ।</p>";
    display(0); $dvi1 = 1;
} else { $dvi1 = 0; }
if (sub(array("dviH","triH","catuH"),$pu,blank(0),0))
{
 $text = two (array("dviH","triH","catuH"),$pu,array("dviz","triz","catuz"),$pu,1);
 echo "<p class = sa >By dvistrizcaturiti kRtvo'rthe (8.3.43) :</p>
        <p class = hn >N.B. This applies only in case of kRtvo'rthe.</p>";
 echo "<p class = sa >द्विस्त्रिश्चतुरिति कृत्वोऽर्थे (८.३.४३):</p>
        <p class = hn >यह नियम सिर्फ कृत्वोऽर्थ में ही लागू होता है ।</p>";
    display(0); $dvi2 = 1;
} else { $dvi2 = 0; }
/* muhusaH pratiSedhaH (vA 4911) */
if (sub(array("muhu"),array("H"),$pu,0)||sub(array("muhu"),array("H"),$ku,0))
{
    $text = three(array("muhu"),array("H"),$pu,array("muhu"),array("H"),$pu,0);
    $text = three(array("muhu"),array("H"),$ku,array("muhu"),array("H"),$ku,0);
    echo "<p class = sa >By muhusaH pratiSedhaH (vA 4911) :</p>";
    echo "<p class = sa >मुहुसः प्रतिषेधः (वा ४९११) :</p>";
    display(0); $muhu1 = 1;
} else { $muhu1 = 0; }
/* kaskAdiSu ca (8.3.48) */
$kaska = array("kaHkaH","kOtaHkut","sarpiHkuRqik","BrAtuHputr","SunaHkarR","sadyaHkAl","sadyaHkI","sAdyaHk","kAMHkAn","DanuHkapAl","bahiHpal","barhiHpal","yajuHpAtr","ayaHkAnt","tamaHkARq","ayaHkARq","medaHpiRq","BAHkar","ahaHkar","kaH+kaH","kOtaH+kut","sarpiH+kuRqik","BrAtuH+putr","SunaH+karR","sadyaH+kAl","sadyaH+kI","sAdyaH+k","kAMH+kAn","DanuH+kapAl","bahiH+pal","barhiH+pal","yajuH+pAtr","ayaH+kAnt","tamaH+kARq","ayaH+kARq","medaH+piRq","BAH+kar","ahaH+kar");
$kaskareplace = array("kaskaH","kOtaskut","sarpizkuRqik","BrAtuzputr","SunaskarR","sadyaskAl","sadyaskI","sAdyask","kAMskAn","DanuzkapAl","bahizpal","barhizpal","yajuzpAtr","ayaskAnt","tamaskARq","ayaskARq","medaspiRq","BAskar","ahaskar","kas+kaH","kOtas+kut","sarpiz+kuRqik","BrAtuz+putr","Sunas+karR","sadyas+kAl","sadyas+kI","sAdyas+k","kAMs+kAn","Danuz+kapAl","bahiz+pal","barhiz+pal","yajuz+pAtr","ayas+kAnt","tamas+kARq","ayas+kARq","medas+piRq","BAs+kar","ahas+kar");
if(sub($kaska,blank(0),blank(0),0))
{
$text = one ($kaska,$kaskareplace,0);
echo "<p class = sa >By kaskAdiSu ca (8.3.48) ";
echo "<p class = sa >कस्कादिषु च (८.३.४८) ";
    display(0); $kaska = 1;
} else { $kaska = 0; }
/* isusoH sAmarthye (8.3.44) and nityaM samAse'nuttarapadasthasya (8.3.45) */ 
if (sub(array("iH","uH",),$ku,blank(0),0) && $dvi1===0 && $dvi2===0 && $muhu1 ===0)
{
    $text = two (array("iH","uH"),$ku,array("iz","uz"),$ku,1);
    echo "<p class = sa >By isusoH sAmarthye (8.3.44) and nityaM samAse'nuttarapadasthasya (8.3.45) :</p>
        <p class = hn >N.B. This applies only in case of sAmarthya. If 'is' or 'us' pratyayas are at the end of first component of a compound, they are mandatorily converted to 'S'.</p>";
    echo "<p class = sa >इसुसोः सामर्थ्ये (८.३.४४) तथा नित्यं समासेऽनुत्तरपदस्थस्य (८.३.४५) :</p>
        <p class = hn >यह तभी लागू होता है जब सामर्थ्य में प्रयोग हुआ हो । यदि 'इस्‌' और 'उस्‌' प्रत्यय उत्तरपद में न हों तब आवश्यक रूप से शकार में परिवर्तन होता है ।</p>";
    display(0); $isu1 = 1;
} else { $isu1 = 0; }
if (sub(array("iH","uH"),$pu,blank(0),0))
{
 $text = two (array("iH","uH"),$pu,array("iz","uz"),$pu,1);
 echo "<p class = sa >By isusoH sAmarthye (8.3.44) and nityaM samAse'nuttarapadasthasya (8.3.45) :</p>
        <p class = hn >N.B. This applies only in case of sAmarthya. If 'is' or 'us' pratyayas are at the end of first component of a compound, they are mandatorily converted to 'S'.</p>";
    echo "<p class = sa >इसुसोः सामर्थ्ये (८.३.४४) तथा नित्यं समासेऽनुत्तरपदस्थस्य (८.३.४५) :</p>
        <p class = hn >यह तभी लागू होता है जब सामर्थ्य में प्रयोग हुआ हो । यदि 'इस्‌' और 'उस्‌' प्रत्यय उत्तरपद में न हों तब आवश्यक रूप से षकार में परिवर्तन होता है ।</p>";
    display(0); $isu2 = 1;
} else { $isu2= 0; }
/* idudupadhasya cApratyayasya (8.3.41) */
$id = array("i","u",);
if (sub($iN,array("H"),$ku,0) && $dvi1===0 && $dvi2===0 && $isu1 ===0 && $isu2 ===0&& $muhu1 ===0 && $pada !== "pratyaya")
{
    $text = three($id,array("H"),$ku,$id,array("z"),$ku,1);
    echo "<p class = sa >By idudupadhasya cApratyayasya (8.3.41) :</p>
        <p class = hn >N.B. : the visarga will be converted to 'S' only if it is not followed by pratyaya.</p>";
    echo "<p class = sa >इदुदुपधस्य चाप्रत्ययस्य (८.३.४१) :</p>
        <p class = hn >यदि परे प्रत्यय न हो तभी षकार में परिवर्तन होता है ।</p>";
    display(0);
}
if (sub($iN,array("H"),$pu,0) && $pada !== "pratyaya")
{
    $text = three($id,array("H"),$pu,$id,array("z"),$pu,1);
    echo "<p class = sa >By idudupadhasya cApratyayasya (8.3.41) :</p>
        <p class = hn >N.B. : the visarga will be converted to 'S' only if it is not followed by pratyaya.</p>";
    echo "<p class = sa >इदुदुपधस्य चाप्रत्ययस्य (८.३.४१) :</p>
        <p class = hn >यदि परे प्रत्यय न हो तभी षकार में परिवर्तन होता है ।</p>";
    display(0);
}
/* ekAdezazAstranimittikasya na Satvam | kaskAdiSu bhrAtuSputrazabdasya pAThAt (vA 4915) */ 
// Pending to code.
/* iNaH SaH (8.3.39) */
if (sub($iN,array("H"),array("pAS","kalp","kAmy","ka","kAMy"),blank(0),0) && $dvi1===0 && $dvi2===0 && $isu1 ===0 && $isu2 ===0 && $muhu1 ===0) 
{
    $text = three($iN,array("H"),array("pAS","kalp","kAmy","ka","kAMy"),$iN,array("z"),array("pAS","kalp","kAmy","ka","kAmy"),0);
    echo "<p class = sa >By iNaH SaH (8.3.39) :</p>";
    echo "<p class = sa >इणः षः (८.३.३९) :</p>";
    display(0); $inah = 1;
} else { $inah = 0; }
/* namaspurasorgatyoH (8.3.40) */
$namas = array("namaH","puraH"); 
if (sub($namas,$ku,blank(0),0))
{
    $text = two($namas,$ku,array("namas","puras"),$ku,1);
    echo "<p class = sa >By namaspurasorgatyoH (8.3.40) :</p>
          <p class = hn >N.B. : The conversion to namas / puras is done only in case it has gati saJjJA.</p>";
    echo "<p class = sa >नमस्पुरसोर्गत्योः (८.३.४०) :</p>
          <p class = hn >यदि गति संज्ञा हो तभी नमस्‌ / पुरस्‌ में परिवर्तन होता है ।</p>";
    display(0); $nama1 = 1;
} else { $nama1 = 0; }
if (sub($namas,$pu,blank(0),0) && $nama1 !==1)
{
    $text = two($namas,$pu,array("namas","puras"),$pu,1);
    echo "<p class = sa >By namaspurasorgatyoH (8.3.40) :</p>
        <p class = hn >N.B. : The conversion to namas / puras is done only in case it has gati saJjJA.</p>";
    echo "<p class = sa >नमस्पुरसोर्गत्योः (८.३.४०) :</p>
          <p class = hn >यदि गति संज्ञा हो तभी नमस्‌ / पुरस्‌ में परिवर्तन होता है ।</p>";
    display(0); $nama2 = 1;
} else { $nama2 = 0; }
/* tiraso'nyatarsyAm (8.3.42) */
if (sub(array("tiraH"),$ku,blank(0),0))
{
    $text = two (array('tiraH'),$ku,array('tiras'),$ku,1);
    echo "<p class = sa >By tiraso'nyatarasyAm  (8.3.42) :</p>";
    echo "<p class = sa >तिरसोऽन्यतरस्याम्‌  (८.३.४२) :</p>";
    display(0); $tir1 = 1;
} else { $tir1 = 0; }
if (sub(array("tiraH"),$pu,blank(0),0))
{
 $text = two (array('tiraH'),$pu,array('tiras'),$pu,1);
 echo "<p class = sa >By tiraso'nyatarasyAm  (8.3.42) :</p>";
 echo "<p class = sa >तिरसोऽन्यतरस्याम्‌  (८.३.४२) :</p>";
    display(0); $tir2 = 1;
} else { $tir2 = 0; }
/* ataH kRkamikaMsakumbhapAtrakuzAkarNISvanavyayasya (8.3.46) */
if (sub(array("aH"),array("kAr","kAm","kAMs","kumBa","pAtra","kuSA","karRI"),blank(0),0) && $nama1 !== 1 && $nama2 !== 1   && $tir1===0 && $tir2===0 )
{
    $text = two(array("aH"),array("kAr","kAm","kAMs","kumBa","pAtra","kuSA","karRI"),array('as'),array("kAr","kAm","kAMs","kumBa","pAtra","kuSA","karRI"),1);
    echo "<p class = sa >By ataH kRkamikaMsakumbhapAtrakuzAkarNISvanavyayasya (8.3.46) :</p>
       <p class = hn > This applies olny when there is compound and the word with 'as' is neither uttarapadastha nor avyaya.</p>";
    echo "<p class = sa >अतः कृकमिकंसकुम्भपात्रकुशाकर्णीष्वनव्ययस्य (८.३.४६) :</p>
       <p class = hn >यह तब लागू होता है, जब समास में 'अस्‌' से अन्त होनेवाला शब्द न तो उत्तरपदस्त हो न ही अव्यय हो ।</p>";
    display(0); $atah = 1;
} else { $atah = 0; }
/* adhazzirasI pade */
if (sub(array("aDaH","SiraH"),array("pada"),blank(0),0)  )
{
    $text = two(array("aDaH","SiraH"),array("pada"),array("aDas","Siras"),array("pada"),1);
    echo "<p class = sa >By aDazzirasI pade (8.3.47) :</p>
       <p class = hn > This applies olny when there is compound and the word 'adhas' or 'ziras' is not uttarapadastha.</p>";
    echo "<p class = sa >अधश्शिरसी पदे (८.३.४७) :</p>
       <p class = hn >यह नियम तभी लागू होता है जब अधस्‌ / शिरस्‌ उत्तरपदस्थ नहीं होते ।</p>";
    display(0); $atah = 1;
} else { $atah = 0; }
/* so'padAdau (8.3.38), pAzakalpakakAmyeSviti vAcyam (vA 5033), anavyayasyeti vAcyam (vA 4902) and kAmye roreveti vAcyam (vA 4902) */ 
// anavyayasyeti vAcyam (vA 4901) is pending to code.
if (sub(array("H"),array("pAS","kalp","kAmy","ka"),blank(0),0) && $inah !== 1 && $nama1 !== 1 && $nama2 !== 1 && $dvi1===0 && $dvi2===0 && $isu1 ===0 && $isu2 ===0 && $tir1===0 && $tir2===0 && $muhu1 ===0  && $atah ===0)
{
    $text = two(array("H"),array("kalp","kAmy","ka","kAMy"),array('s'),array("kalp","kAmy","ka","kAMy"),0);
    $text = two(array("H"),array("pAS"),array('s'),array("pAS"),0);
    if (preg_match('/[sr]$/',$first))
    {
        $text = one(array('skAmy','skAMy'),array('HkAmy','HkAMy'),1);      
    }
    echo "<p class = sa >By so'padAdau (8.3.38), pAzakalpakakAmyeSviti vAcyam (vA 5033), anavyayasyeti vAcyam (vA 4902) and kAmye roreveti vAcyam (vA 4902) :</p>";
    echo "<p class = sa >सोऽपदादौ (८.३.३८), पाशकल्पककाम्येष्विति वाच्यम्‌ (वा ५०३३), अनव्ययस्येति वाच्यम्‌ (वा ४९०२) तथा काम्ये रोरेवेति वाच्यम् (वा ४९०२) :</p>";
    display(0);
}
/* zarpare visarjanIyaH (8.3.35) */
if (sub(array("H"),prat('Kr'),prat('Sr'),0) )
{
echo "<p class = sa >By zarpare visarjanIyaH (8.3.35) :</p>";
echo "<p class = sa >शर्परे विसर्जनीयः (८.३.३५) :</p>";
display(0); $zarpare = 1;
} else { $zarpare = 0; }
/* kupvoH &k&pau ca (8.3.37) */ // <p class = hn >Note that we have used & as jihvAmUlIya and * as upadhmAnIya.
if((sub(array("H"),$ku,blank(0),0)||sub(array("H"),$pu,blank(0),0)) && $kaska !== 1 && $zarpare ===0)
{
$text = two(array("H"),$ku,array("&"),$ku,1);
$text = two(array("H"),$pu,array("&"),$pu,1);
echo "<p class = sa >By kupvoH &k&pau ca (8.3.37). :</p>";
echo "<p class = sa >कुप्वोः ᳲकᳲपौ च (८.३.३७). :</p>";
display(0); $kupvo = 1;
} else {$kupvo = 0; }
/* visarjanIyasya saH (8.3.34) */ 
if(sub(array("H"),prat('Kr'),blank(0),0) && $zarpare !==1 && $kupvo ===0)
{
$text = two(array("H"),prat('Kr'),array("s"),prat('Kr'),0);
$zarpare = 2;
echo "<p class = sa >By visarjanIyasya saH (8.3.34) :</p>";
echo "<p class = sa >विसर्जनीयस्य सः (८.३.३४) :</p>";
display(0);
}
/* vA zari (8.3.36) */
if(sub(array("s"),prat('Sr'),blank(0),0) && $zarpare === 2)
{
$text = two(array("s"),array("S","z","s"),array("H"),array("S","z","s"),1);
echo "<p class = sa >By vA zari (8.3.36) :</p>";
display(0); $zarpare = 3;
}
/* kharpare zari vA visargalopo vaktavyaH (vA 4906) */
if(sub(array("H"),prat('Sr'),prat('Kr'),0) && $zarpare === 3)
{
$text = three(array("H"),prat('Sr'),prat('Kr'),array(""),prat('Sr'),prat('Kr'),1);
echo "<p class = sa >By kharpare zari vA visargalopo vaktavyaH (vA 4906) :</p>";
echo "<p class = sa >खर्परे शरि वा विसर्गलोपो वक्तव्यः (वा ४९०६) :</p>";
display(0);
}
/* apadAntasya mUrdhanyaH (8.3.55), iNkoH (8.3.57) and AdezapratyayayoH (8.3.59) */
// Not coded perfectly, only according to the need of vibhaktis. 
if((sub(array("i","I","u","U","f","F","x","X","e","o","E","O","h","y","v","r","l","k","K","g","G","N"),array("s"),array("u","Am"),0)) &&  in_array($so,array("Am","sup")))
{
$text = last(array("su","sAm",),array("zu","zAm"),0);
echo "<p class = sa >By apadAntasya mUrdhanyaH (8.3.55), iNkoH (8.3.57) and AdezapratyayayoH (8.3.59) :</p>";
echo "<p class = sa >अपदान्तस्य मूर्धन्यः (८.३.५५), इण्कोः (८.३.५७) तथा आदेशप्रत्यययोः (८.३.५९) :</p>";
display(0);
}
/* raSAbhyAM no NaH samAnapade (8.4.1) */
// pUrvasmAdapi vidhau sthAnivadbhAvaH , pUrvatrAsiddhe na sthAnivat (vA 433) and tasya doSaH saMyogAdilopalatvaNatveSu (vA 440) are pending to code.
if($pada === "pratyaya" && sub(array("r","z"),array("n"),blank(0),0))
{
$text = two(array("r","z"),array("n"),array("r","z"),array("R"),0);
echo "<p class = sa >By raSAbhyAM no NaH samAnapade (8.4.1) :</p>";
echo "<p class = sa >रषाभ्यां नो णः समानपदे (८.४.१) :</p>";
display(0);   
}
/* aTkupvAGnumvyavAye'pi (8.4.2) and padAntasya (8.4.37) */
/* RvarNAnnasya NatvaM vAcyam (vA 4969) */
// The issue is identifying samAnapada. Can't be coded properly as of now.
$ras = '/([rzfF])([aAiIuUfFxXeoEOhyvrkKgGNpPbBmM+]*)([n])/';
$rasend = '/([rzfF])([aAiIuUfFxXeoEOhyvrkKgGNpPbBmM+]*)([n])$/';
$ras1 = '$1$2R';
if (arr($text,$ras) && !arr($text,$rasend)) 
{ 
    foreach ($text as $value)
    {
        if (preg_match('/([rzfF])([aAiIuUfFxXeoEOhyvrkKgGNpPbBmM+]*)([n])/',$value))
        {
        $value1[] = preg_replace($ras,$ras1,$value);
        }
        else
        {
        $value1[] = $value;    
        }
    }
$text = $value1;
$value1 = array();
if(arr($text,'/([rz])([aAiIuUfFxXeoEOhyvrkKgGNpPbBmM+]*)([R])/'))
{
echo "<p class = sa >By aTkupvAGnumvyavAye'pi (8.4.2) :</p>";
echo "<p class = sa >अट्कुप्वाङ्नुम्व्यवायेऽपि (८.४.२) :</p>";     
}
if(arr($text,'/([fF])([aAiIuUfFxXeoEOhyvrkKgGNpPbBmM+]*)([R])/'))
{
echo "<p class = sa >By RvarNAnnasya NatvaM vAcyam (vA 4969) :</p>";
echo "<p class = sa >ऋवर्णान्नस्य णत्वं वाच्यम्‌ (वा ४९६९) :</p>";     
}
display(0);
}
/* Patch to remove the + sign */
if (arr($text,'/['.pc('hl').' ][+]['.pc('hl').']/') || arr($text,'/[HM!][+]['.pc('hl').']/'))
{
$text = three($hl,array("+"," +"),$hl,$hl,array(""," "),$hl,0);    
$text = three(array("H","M","!"),array("+"),$hl,array("H","M","!"),array(""),$hl,0);    
}
if ( arr($text,'/['.pc('ac').'HM! ][+]['.pc('ac').']/') || arr($text,'/[HM!][+]['.pc('ac').']/') )
{ 
$text = one(array("+"),array(" "),0);
}
if ( arr($text,'/['.pc('ac').' ][+]['.pc('hl').'MH]/') || arr($text,'/[+]$/') )
{ 
$text = one(array("+"),array(""),0);
}
if ( arr($text,'/['.pc('hl').'][+]['.pc('ac').']/') )
{ 
$text = one(array("+"),array(""),0);
}
/* stoH zcunA zcuH (8.4.40) */
$stu = array("s","t","T","d","D","n");
$zcu = array("S","c","C","j","J","Y");
if(sub($stu,$zcu,blank(0),0))
{
$text = two(array("s"),$zcu,array("S"),$zcu,0);
$text = two(array("t"),$zcu,array("c"),$zcu,0);
$text = two(array("T"),$zcu,array("C"),$zcu,0);
$text = two(array("d"),$zcu,array("j"),$zcu,0);
$text = two(array("D"),$zcu,array("J"),$zcu,0);
$text = two(array("n"),$zcu,array("Y"),$zcu,0);
echo "<p class = sa >By stoH zcunA zcuH (8.4.40) :</p>";
echo "<p class = sa >स्तोः श्चुना श्चुः (८.४.४०) :</p>";
display(0);
}
/* stoH zcunA zcuH (8.4.40) and zAt (8.4.44) */
$zcu1= array("c","C","j","J","Y");
if(sub($zcu1,$stu,blank(0),0))
{
$text = two($zcu1,array("s"),$zcu1,array("S"),0); 
$text = two($zcu1,array("t"),$zcu1,array("c"),0); 
$text = two($zcu1,array("T"),$zcu1,array("C"),0); 
$text = two($zcu1,array("d"),$zcu1,array("j"),0);
$text = two($zcu1,array("D"),$zcu1,array("J"),0); 
$text = two(array("S"),array("s"),array("S"),array("S"),0); 
    echo "<p class = sa >By stoH zcunA zcuH (8.4.40) and zAt (8.4.44) :</p>";
    echo "<p class = sa >स्तोः श्चुना श्चुः (८.४.४०) तथा शात्‌ (८.४.४४) :</p>";
display(0);
}
/* anAmnavatinagarINAmiti vAcyam (vA 5016) */
$shtu = array("z","w","W","q","Q","R",);
if (sub($shtu,array("nAm","navat","nagar"),blank(0),0))
{
$text = two($shtu,array("nAm","navat","nagar"),$shtu,array("RAm","Ravat","Ragar"),0);
echo "<p class = sa >By na padAntATToranAm (8.4.42) and anAmnavatinagarINAmiti vAcyam (vA 5016) :</p>";
echo "<p class = sa >न पदान्ताट्टोरनाम्‌ (८.४.४२) तथा अनाम्नवतिनगरीणामिति वाच्यम्‌ (वा ५०१६) :</p>";
display(0);
if (sub($shtu,array("Ravat","Ragar"),blank(0),0))
{
$text = two($shtu,array("Ravat","Ragar"),array("R","R","R","R","R","R"),array("Ravat","Ragar"),0);
    echo "<p class = sa >By stoH STunA STuH (8.4.41) :</p>";
    echo "<p class = sa >स्तोः ष्टुना ष्टुः (८.४.४१) :</p>";
    display(0);
}
if (sub($shtu,array("RAm"),blank(0),0))
{
$text = two($shtu,array("RAm"),array("R","R","R","R","R","R"),array("RAm"),0);
    echo "<p class = sa >By yaro'nunAsike'nunAsiko vA (8.4.45) and pratyaye bhASAyAm nityam (vA) :</p>";
    echo "<p class = sa >यरोऽनुनासिकेऽनुनासिको वा (८.४.४५) तथा प्रत्यये भाषायां नित्यम्‌ (वार्तिक) :</p>";
    display(0);
}
} 
/* stoH STunA STuH (8.4.41) and na padAntATToranAm (8.4.41) and toH Si (8.4.43) */
$Tu = array("w","W","q","Q","R",); $tu = array("t","T","d","D","n");
if((sub($shtu,$stu,blank(0),0)|| sub($stu,$shtu,blank(0),0)) )
{
$text = two(array("z"),$stu,array("z"),$shtu,0);
$text = two(array("s"),$shtu,array("z"),$shtu,0);
$text = two(array("t"),$Tu,array("w"),$Tu,0);
$text = two(array("T"),$Tu,array("W"),$Tu,0);
$text = two(array("d"),$Tu,array("q"),$Tu,0);
$text = two(array("D"),$Tu,array("Q"),$Tu,0);
$text = two(array("n"),$Tu,array("R"),$Tu,0);
    if ($pada === "pratyaya" && (sub($Tu,$tu,blank(0),0)))
    {
        $text = two(array("w"),$tu,array("w"),$Tu,0);
        $text = two(array("W"),$tu,array("W"),$Tu,0);
        $text = two(array("q"),$tu,array("q"),$Tu,0);
        $text = two(array("Q"),$tu,array("Q"),$Tu,0);
        $text = two(array("R"),$tu,array("R"),$Tu,0);
    }       
    echo "<p class = sa >By stoH STunA STuH (8.4.41) and na padAntATToraNam (8.4.42) and toH Si (8.4.43) :</p>";
    echo "<p class = sa >स्तोः ष्टुना ष्टुः (८.४.४१), न पदान्ताट्टोरणाम्‌ (८.४.४२) तथा तोः षि (८.४.४३) :</p>";
    display(0);
}
/* yaro'nunAsike'nunAsiko vA (8.4.45) */ // this is applicable to only sparzas.
$yara = array("J","B","G","Q","D","j","b","g","q","d","K","P","C","W","T","c","w","t","k","p");
$anunasikarep = array("Y","m","N","R","n","Y","m","N","R","n","N","m","Y","R","n","Y","R","n","N","m");
$anunasika = array("N","Y","R","n","m");
if (preg_match('/['.flat($yara).']$/',$first) && preg_match('/^['.flat($anunasika).']/',$second) && $pada === "pada")
{
$text = two($yara,$anunasika,$anunasikarep,$anunasika,1);
echo "<p class = sa >By yaro'nunAsike'nunAsiko vA (8.4.45) :</p>";
echo "<p class = sa >यरोऽनुनासिकेऽनुनासिको वा (८.४.४५) :</p>";
display(0);
}
if (preg_match('/['.flat($yara).']$/',$first) && preg_match('/^['.flat($anunasika).']/',$second) && $pada === "pratyaya")
{
$text = two($yara,$anunasika,$anunasikarep,$anunasika,0);
echo "<p class = sa >By yaro'nunAsike'nunAsiko vA (8.4.45) :</p>";
echo "<p class = sa >यरोऽनुनासिकेऽनुनासिको वा (८.४.४५) :</p>";
display(0);
}
/* nAdinyAkroze putrasya (8.4.48) */
if (sub(array('putrAdin'),blank(0),blank(0),0))
{
    echo "<p class = sa >By nAdinyAkroze putrasya (8.4.48) - If Akroza is meant : The dvitva doesn't happen. Otherwise dvitva will happen.</p>";
    echo "<p class = sa >नादिन्याक्रोशे पुत्रस्य (८.४.४८) - यदि आक्रोश के अर्थ में प्रयुक्त हुआ है, तब द्वित्व नहीं होगा । अन्यथा द्वित्व होगा ।</p>";
}
/* vA hatajagdhayoH (vA 5022) */
if (sub(array("putrahatI"),blank(0),blank(0),0))
{
echo "<p class = sa >By vA hatajagdhayoH (vA 5022) :</p>";
echo "<p class = sa >वा हतजग्धयोः (वा ५०२२) :</p>";
display(0);
}
if (sub(array('putrajagDI'),blank(0),blank(0),0))
{
echo "<p class = sa >By vA hatajagdhayoH (vA 5022) :</p>";
echo "<p class = sa >वा हतजग्धयोः (वा ५०२२) :</p>";
display(0);
}
/*anaci ca (8.4.47)*/ // Here the sudhI + upAsya - what about the Asy - Assy is possbile ? Code gives it. But there are 4 options. Code gives two only.
// The cause for using $hrasva instead of $ac is that the dIrgha vowels are debarred by dIrghAdAcAyANAm.
// Here instead of using pratyAhAra hl, we shall do manual enumeration of all the members. Bexause of "anusvAravisargajihvAmUlIyopadhmAnIyayamAnAmakAropari zarSu ca pAThasyopasaGkhyAtatvenAnusvArasyApyactvAt (in derivation of samskAra) 
$hrasvaplus = array("M","!","'");
$hala1 = array("y","v","l","Y","m","N","R","n","J","B","G","Q","D","j","b","g","q","d","K","P","C","W","T","c","w","t","k","p","S","z","s","M",);
$hala2 = array("h","y","v","r","l","Y","m","N","R","n","J","B","G","Q","D","j","b","g","q","d","K","P","C","W","T","c","w","t","k","p","S","z","s","M",);
if(sub($hrasva,$hala1,$hala2,0))
{
    $text = dvitva($hrasva,$hala1,$hala2,array(""),2,1);
    if (sub($dirgha,$hala1,$hala2,0))
    {
    echo "<p class = sa >By anaci ca (8.4.47) and dIrghAdAcAryANAm (8.4.52) :</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७): तथा दीर्घादाचार्याणाम्‌ (८.४.५२) :</p>";     
    }
    else
    {
    echo "<p class = sa >By anaci ca (8.4.47):</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७):</p>";     
    }
display(1);
}
if(sub($hrasvaplus,$hl,$hala2,0))
{
    $text = dvitva($hrasvaplus,$hl,$hala2,array(""),2,1);
    if (sub($dirgha,$hl,$hala2,0))
    {
    echo "<p class = sa >By anaci ca (8.4.47) and dIrghAdAcAryANAm (8.4.52) :</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७): तथा दीर्घादाचार्याणाम्‌ (८.४.५२) :</p>";     
    }
    else
    {
    echo "<p class = sa >By anaci ca (8.4.47):</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७):</p>";     
    }
    display(1);
}
if(checkarray($dirgha,$hl,array('r','l'),blank(0))!==0 && $sthanivadbhav===1) 
{
$text = dvitva($dirgha,$hala1,$hala2,array(""),2,1);
    if (sub($dirgha,$hl,array('r','l'),0))
    {
    echo "<p class = sa >By anaci ca (8.4.47) and dIrghAdAcAryANAm (8.4.52) :</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७): तथा दीर्घादाचार्याणाम्‌ (८.४.५२) :</p>";     
    }
    else
    {
    echo "<p class = sa >By anaci ca (8.4.47):</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७):</p>";     
    }
display(1);
}
/* By anaci ca (according to mahAbhASya example of vAkk) */ 
if (arr($text,'/['.flat($ac).']['.flat($hl).']$/') || (preg_match('/['.flat($ac).']['.flat($hl).']$/',$first) && $input === $first ))
{
    foreach ($text as $value)
    {
        $split = str_split($value);
        $post = $split[count($split)-1];
        if (in_array($post,$hl))
        {
        $pre = chop($value,$post); 
        $value1[] = str_replace($value,$pre.$post.$post,$value);
        }
        else
        {
            $value1[] = $value;
        }
    }
    $text = array_merge($text,$value1);
    $text = array_unique($text);
    $text = array_values($text);
    $value1 = array();
    if (sub($dirgha,$hala1,$hala2,0))
    {
    echo "<p class = sa >By anaci ca (8.4.47) and dIrghAdAcAryANAm (8.4.52) :</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७): तथा दीर्घादाचार्याणाम्‌ (८.४.५२) :</p>";     
    }
    else
    {
    echo "<p class = sa >By anaci ca (8.4.47):</p>"; 
    echo "<p class = sa >अनचि च (८.४.४७):</p>";     
    }
    display(1);
}
/* zaraH khayaH (vA 5019) */
$shara = array("S","z","s",);
if (sub($shara,prat('Ky'),blank(0),0))
{
$text = dvitva($shara,prat('Ky'),array(""),array(""),2,1);
echo "<p class = sa >zaraH khayaH (vA 5019) :</p>";
echo "<p class = sa >शरः खयः (वा ५०१९) :</p>";
display(1);
}
/* aco rahAbhyAM dve (8.4.46) */ 
$rh = array("r","h");
if (sub($ac,$rh,prat('yr'),0))
{
    if (sub($rh,array("S","z","s"),$ac,0))
    {
        echo "<p class = sa >By zaro'ci (8.4.48) :</p>";
        echo "<p class = hn >N.B.: zaro'ci bars application of aco rahAbhyAm dve</p>";
        echo "<p class = sa >शरोऽचि (८.४.४८) :</p>";
        echo "<p class = hn >शरोऽचि अचो रहाभ्यां द्वे के प्रयोग का निषेध करता है ।</p>";
    }
    else
    {
        $text = dvitva($ac,$rh,prat('yr'),array(""),3,1);
        echo "<p class = sa >By aco rahAbhyAM dve (8.4.46) :</p>";
        echo "<p class = sa >अचो रहाभ्यां द्वे (८.४.४६) :</p>";
        display(1);
    }
}
/* triprabhRtiSu zAkaTAyanasya (8.4.50)*/
if (checkarray($ac,$hl,$hl,$hl) === 1)
{
echo "<p class = hn >N.B.: By triprabhRtiSu zAkaTAyanasya (8.4.50), the dvitva is optionally not done in cases where there are more than three hals appearing consecutively. e.g. indra - inndra.  </p>";
echo "<p class = hn >त्रिप्रभृतिषु शाकटायनस्य (८.४.५०) - तीन या उससे ज्यादा हल्‌ अगर हो तब शाकटायन के मत में द्वित्व नहीं होता है ।</p>";
}
/* sarvatra zAkalyasya (8.4.51) */
// It is not coded separately. It is sent as a message in all display function when 1 is selected as option. 
/* dIrghAdAcAryANAm (8-4-52) */
// Not coded separately, because we did dvitva only for $hrasva, and not for 'ac'. So this is already taken care of.
/* jhalAM jaz jhaSi (8.4.53) */
if(sub(prat('Jl'),prat('JS'),blank(0),0))
{
$text = two(prat('Jl'),prat('JS'),savarna(prat('Jl'),prat('jS')),prat('JS'),0);
echo "<p class = sa >By jhalAM jaz jhazi (8.4.53):</p>";
echo "<p class = sa >झलां जश्‌ झशि (८.४.५३):</p>";
display(0);
}
/* yaNo mayo dve vAcye (vA 5018) yaN in paJcamI and may in SaSThI)*/
if (sub($hrasva,prat('yR'),prat('my'),0))
{
$text = dvitva(prat('yR'),prat('my'),array(""),array(""),2,1);
echo "<p class = sa >By yaNo mayo dve vAcye (yaN in paJcamI and may in SaSThI) (vA 5018) :</p>";
echo "<p class = sa >यणो मयो द्वे वाच्ये (यण्‌ पञ्चमी तथा मय्‌ षष्ठी) (वा ५०१८) :</p>";
display(1); 
}
if (sub($dirgha,prat('yR'),prat('my'),0) && $sthanivadbhav ===1)
{
$text = dvitva(prat('yR'),prat('my'),array(""),array(""),2,1);
echo "<p class = sa >By yaNo mayo dve vAcye (yaN in paJcamI and may in SaSThI) (vA 5018) :</p>";
echo "<p class = sa >यणो मयो द्वे वाच्ये (यण्‌ पञ्चमी तथा मय्‌ षष्ठी) (वा ५०१८) :</p>";
display(1); 
}
/* yaNo mayo dve vAcye (vA 5018) may in paJcamI and yaN in SaSThI)*/
if (sub($hrasva,prat('my'),prat('yR'),0))
{
$text = dvitva(prat('my'),prat('yR'),array(""),array(""),2,1);
echo "<p class = sa >By yaNo mayo dve vAcye (may in paJcamI and yaN in SaSThI) (vA 5018):</p>";
echo "<p class = sa >यणो मयो द्वे वाच्ये (मय्‌ पञ्चमी तथा यण्‌ षष्ठी) (वा ५०१८) :</p>";
display(1);
}
if (sub($dirgha,prat('my'),prat('yR'),0) && $sthanivadbhav ===1)
{
$text = dvitva(prat('my'),prat('yR'),array(""),array(""),2,1);
echo "<p class = sa >By yaNo mayo dve vAcye (may in paJcamI and yaN in SaSThI) (vA 5018):</p>";
echo "<p class = sa >यणो मयो द्वे वाच्ये (मय्‌ पञ्चमी तथा यण्‌ षष्ठी) (वा ५०१८) :</p>";
display(1);
}
/* vA'vasAne (8.4.54) */
if (arr($text,'/['.pc('Jl').']$/'))
{
$text = last(prat('Jl'),savarna(prat('Jl'),prat('cr')),1);
echo "<p class = sa >By vA'vasAne (8.4.54) :</p>";
echo "<p class = sa >वाऽवसाने (८.४.५४) :</p>";
    display(0);
}
/* khari ca (8.4.55) */ 
$Jl1 = array("J","B","G","Q","D","j","b","g","q","d","K","P","C","W","T","c","w","t","k","p","S","z","s","h");
$Jl2 = array("J","B","G","Q","D","j","b","g","q","d","K","P","C","W","T","h");
while(sub($Jl2,prat('Kr'),blank(0),0) !== false)
{
if (sub($Jl1,prat('Kr'),blank(0),0) || $dhut === 1)
{
$text = two($Jl1,prat('Kr'),savarna(prat('Jl'),prat('cr')),prat('Kr'),0);
echo "<p class = sa >By khari ca (8.4.55) :</p>";
echo "<p class = sa >खरि च (८.४.५५) :</p>";
display(0);
}
}
/* aNo'pragRhyasyAnunAsikaH (8.4.57) */
if (arr($text,'/[aAiIuUfFxX]$/'))
{
    foreach($text as $value)
    {
    $value2[] = $value."!";
    }
    $value2 = array_merge($text,$value2);
    $value2 = array_unique($value2);
    $text = array_values($value2);
    $value2 = array();
    echo "<p class = sa >By aNo'pragRhyasyAnunAsikaH (8.4.57) :</p>";
    echo "<p class = sa >अणोऽप्रगृह्यस्यानुनासिकः (८.३.५७) :</p>";
display(0);
}
/* anusvArasya yayi parasavarNaH (8.4.58) and vA padAntasya (8.4.59) */
$mm = array("My","Mv","Mr","Ml","MY","Mm","MN","MR","Mn","MJ","MB","MG","MQ","MD","Mj","Mb","Mg","Mq","Md","MK","MP","MC","MW","MT","Mc","Mw","Mt","Mk","Mp");
$pa = array("!yy","!vv","!rr","!ll","YY","mm","NN","RR","nn","YJ","mB","NG","RQ","nD","Yj","mb","Ng","Rq","nd","NK","mP","YC","RW","nT","Yc","Rw","nt","Nk","mp");
if (sub(array("M"),prat('yr'),blank(0),0))
{
$text = one($mm,$pa,1);
echo "<p class = sa >By anusvArasya yayi parasavarNaH (8.4.58) and vA padAntasya (8.4.59) :</p>
    <p class = hn >N.B.: The change of anusvARa to parasavarNa is mandatory for non padAnta conjoints. For padAnta conjoints, it is optional.</p>";
echo "<p class = sa >अनुस्वारस्य ययि परसवर्णः (८.४.५८) तथा वा पदान्तस्य (८.४.५९) :</p>
    <p class = hn >पदान्त में पाक्षिक है । अपदान्त के लिए अनिवार्य है ।</p>";
display(0);
}
/* torli (8.4.60) */
$to = array("tl","Tl","dl","Dl","nl");
$lirep = array("ll","ll","ll","ll","l!l",);
while(sub($to,blank(0),blank(0),0) !== false)
{
if (sub($to,blank(0),blank(0),0))
{
$text = one($to,$lirep,0);
echo "<p class = sa >By torli (8.4.60) :</p>";
echo "<p class = sa >तोर्लि (८.४.६०) :</p>";
display(0);
}
}
/* jhayo ho'nyatarasyAm (8.4.62) */ 
$Jy = array("Jh","Bh","Gh","Qh","Dh","jh","bh","gh","qh","dh","Kh","Ph","Ch","Wh","Th","ch","wh","th","kh","ph",);
$h1 = array("JJ","BB","GG","QQ","DD","jJ","bB","gG","qQ","dD","KG","PB","CJ","WQ","TD","cJ","wQ","tD","kG","pB",);
if (sub($Jy,blank(0),blank(0),0)) 
{
$text = one($Jy,$h1,1);
echo "<p class = sa >By jhayo ho'nyatarasyAm (8.4.62) :</p>";
echo "<p class = sa >झयो होऽन्यतरस्याम्‌ (८.४.६२) :</p>";
display(0);
}
/* zazCho'Ti (8.4.63) and ChatvamamIti vAcyam (vA 5025) */
$Jy = array("JS","BS","GS","QS","DS","jS","bS","gS","qS","dS","KS","PS","CS","WS","TS","cS","wS","tS","kS","pS",);
$h1 = array("JC","BC","GC","QC","DC","jC","bC","gC","qC","dC","KC","PC","CC","WC","TC","cC","wC","tC","kC","pC",);
$aT = array("a","A","i","I","u","U","f","F","x","X","e","o","E","O","h","y","v","r","l","Y","m","G","R","n");
if(sub($Jy,$aT,blank(0),0) && $pada === "pada")
{
$text = two($Jy,$aT,$h1,$aT,1);
echo "<p class = sa >By zazCho'Ti (8.4.63) and ChatvamamIti vAcyam (vA 5025) :</p>";
echo "<p class = sa >शश्छोऽटि (८.४.६३) तथा छत्वममीति वाच्यम्‌ (वा ५०२५) :</p>";
display(0);
}
/* halo yamAM yami lopaH (8.4.64) */ 
$duplicate = array("NN","YY","RR","nn","mm","yy","rr","ll","vv",);
$dup = array("N","Y","R","n","m","y","r","l","v",);
$hl = array("k","K","g","G","N","c","C","j","J","Y","w","W","q","Q","R","t","T","d","D","n","p","P","b","B","m","y","r","l","v","S","z","s","h");
if (sub($hl,prat('ym'),prat('ym'),0))
{
$text = two($hl,$duplicate,$hl,$dup,1);
echo "<p class = sa >By halo yamAM yami lopaH (8.4.64) :</p>";
echo "<p class = sa >हलो यमां यमि लोपः (८.४.६४) :</p>";
display(0);
}
/* jharo jhari savarNe (8.4.65) */ 
if(sub(prat('hl'),prat('Jr'),prat('Jr'),0))
{
for ($i=0;$i<count(prat('Jr'));$i++)
{$kkk = array("k","K","g","G"); $ccc = array("c","C","j","J","S");
$www = array("w","W","q","Q","z"); $ttt = array("t","T","d","D","s");
$ppp = array("p","P","b","B");
$text = three(prat('hl'),$kkk,$kkk,prat('hl'),blank(4),$kkk,1);
$text = three(prat('hl'),$ccc,$ccc,prat('hl'),blank(5),$ccc,1);
$text = three(prat('hl'),$www,$www,prat('hl'),blank(5),$www,1);
$text = three(prat('hl'),$ttt,$ttt,prat('hl'),blank(5),$ttt,1);
$text = three(prat('hl'),$ppp,$ppp,prat('hl'),blank(4),$ppp,1);
}
echo "<p class = sa >By jharo jhari savarNe (8.4.65) :</p>";
echo "<p class = sa >झरो झरि सवर्णे (८.४.६५) :</p>";
display(0);
}
/* Final Display */
echo "<p class = sa >Final forms are :</p>";
echo "<p class = sa >आखिरी रूप हैं :</p>";
display(0);
?>
</body>
</html>