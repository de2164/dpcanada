<?
// Translated by user 'cbgrf' at pgdp.net, 2006-08-26

$relPath='../../c/pinc/';
include($relPath.'site_vars.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'connect.inc');
include($relPath.'theme.inc');
new dbConnect();
$no_stats=1;
theme('Reglas de Revisi�n','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>

<h1 align="center">Reglas de Revisi�n</h1>

<h3 align="center">Version 1.9.c, generated January 1, 2006
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="dochist.php"><font size="-1">(Revision History)</font></a></h3>

<h4>Proofreading Guidelines <a href="proofreading_guidelines_francaises.php">in French</a> /
    Directives de Relecture et correction <a href="proofreading_guidelines_francaises.php">en fran&ccedil;ais</a><br>

	Proofreading Guidelines <a href="proofreading_guidelines_portuguese.php">in Portuguese</a> /
    Regras de Revis&atilde;o <a href="proofreading_guidelines_portuguese.php">em Portugu&ecirc;s</a></h4>

<h4>V&eacute;alos <a href="../quiz/start.php">Proofreading Quiz and Tutorial</a></h4>

<table border="0" cellspacing="0" width="100%" summary="Proofreading Guidelines">
  <tbody>

  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="center"><font size="+2"><b>Tabla de Contenidos</b></font></td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
    <ul>

      <li><a href="#prime">Regla principal</a></li>
      <li><a href="#about">Acerca de este documento</a></li>
      <li><a href="#comments">Comentarios del proyecto</a></li>
      <li><a href="#forums">Foro/Discusi�n sobre este proyecto</a></li>
      <li><a href="#prev_pg">Corrigiendo errores en anteriores  p�ginas </a></li>
    </ul>

    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
      <ul>
        <li><font size="+1">C&oacute;mo revisar...</font></li>
      </ul>

    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#line_br">Saltos de l&iacute;nea</a></li>
        <li><a href="#double_q">Comillas dobles</a></li>

        <li><a href="#single_q">Comillas simples</a></li>
        <li><a href="#quote_ea">Comillas en cada l�nea</a></li>
        <li><a href="#period_s">Punto final de las frases</a></li>
        <li><a href="#punctuat">Puntuaci�n</a></li>
        <li><a href="#period_p">Puntos suspensivos "..."</a></li>

        <li><a href="#contract">Contracciones</a></li>
        <li><a href="#extra_sp">Varios espacios en blanco entre palabras</a></li>
        <li><a href="#trail_s">Espacios en blanco al final de la l�nea</a></li>
        <li><a href="#line_no">N�meros de l�nea</a></li>
        <li><a href="#italics">Textos en cursiva y negrita</a></li>
        <li><a href="#supers">Super�ndice</a></li>

        <li><a href="#subscr">Sub�ndice</a></li>
        <li><a href="#font_sz">Cambio de tama�o de fuente</a></li>
        <li><a href="#small_caps">Palabras en may�sculas peque�as-versalitas</a></li>
        <li><a href="#drop_caps">Letras may�sculas ornamentadas al inicio del p�rrafo, frase o secci�n</a></li>
        <li><a href="#a_chars">Caracteres acentuados</a></li>
        <li><a href="#d_chars">Signos diacr�ticos</a></li>

        <li><a href="#f_chars">Caracteres no-Latinos</a></li>
        <li><a href="#fract_s">Fracciones</a></li>
        <li><a href="#em_dashes">Guiones, el signo menos "-" y rayas</a></li>
        <li><a href="#eol_hyphen">Gui�n al final de la l�nea</a></li>
        <li><a href="#eop_hyphen">Gui�n al final de la p�gina</a></li>
        <li><a href="#para_space">P�rrafos</a></li>

        <li><a href="#mult_col">M�ltiples Columnas</a></li>
        <li><a href="#blank_pg">P�gina en blanco</a></li>
        <li><a href="#page_hf">Encabezado y pie de p�gina</a></li>
        <li><a href="#chap_head">T�tulos de cap�tulo</a></li>
        <li><a href="#illust">Ilustraciones - Im�genes</a></li>
        <li><a href="#footnotes">Notas al pie de p�gina</a></li>

        <li><a href="#poetry">Poes�a</a></li>
        <li><a href="#para_side">Notas al margen</a></li>
        <li><a href="#tables">Tablas</a></li>
        <li><a href="#title_pg">Portada&mdash;Contraportada</a></li>
        <li><a href="#toc">La tabla de contenidos</a></li>
        <li><a href="#bk_index">�ndices</a></li>

        <li><a href="#play_n">Obras de teatro&mdash;Nombres de actores&mdash;Directrices esc�nicas</a></li>
        <li><a href="#anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></li>
        <li><a href="#prev_notes">Notas y comentarios de revisores anteriores</a></li>
      </ul>
    </td>
  </tr>
   <tr>

    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver" align="left">
    <ul>
      <li><font size="+1">Problemas comunes</font></li>
    </ul>
    </td>
  </tr>
  <tr>

    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="white" align="left">
      <ul style="margin-left: 3em;">
        <li><a href="#OCR_1lI">Problemas de OCR: 1-l-I</a></li>
        <li><a href="#OCR_0O">Problemas de OCR: 0-O</a></li>
        <li><a href="#OCR_scanno">Problemas de OCR: Scannos</a></li>
        <li><a href="#hand_notes">Notas escritas a mano</a></li>

        <li><a href="#bad_image">Im�genes de baja calidad</a></li>
        <li><a href="#bad_text">Imagen que no corresponde al texto</a></li>
        <li><a href="#round1">Errores de revisores anteriores</a></li>
        <li><a href="#p_errors">Errores de impresi�n / Ortograf�a</a></li>
        <li><a href="#f_errors">Errores de datos o hechos en el texto</a></li>
        <li><a href="#uncertain">Inseguridades</a></li>

      </ul>
    </td>
  </tr>
  <tr>
    <td width="1" bgcolor="silver">&nbsp;</td>
    <td bgcolor="silver">&nbsp;</td>
  </tr>
 </tbody>
</table>

<h3><a name="prime">Regla principal</a></h3>
<p><em>�No cambie lo que escribi� el autor!</em>
</p>
<p>El libro electr�nico, que ver� el lector, posiblemente dentro de muchos
a�os, debe decirle lo que quer�a decir el autor. Si el autor escribi�
una palabra de una manera extra�a, la dejamos como est�. Si el autor
escribi� de una forma racista, tambi�n se dejan las palabras como est�n.
Si el autor usaba cursiva, negrita o pon�a una nota al pie cada tres
palabras, debemos usar cursiva, negrita y dejar las notas donde est�n.
Somos revisores, <b>no</b> editores. (Vea <a href="#p_errors">Errores de impresi�n /
ortograf�a</a>. para entender c�mo manejar errores de impresi�n)
</p>
<p>Cambiamos solamente peque�os detalles de ortograf�a que no afecten el
sentido de lo escrito por el autor. Por ejemplo, unimos palabras que
fueron partidas al final de la l�nea. (Vea <a href="#eol_hyphen">Guiones al final de la
l�nea.</a>)
</p>
<p>Cambios de este tipo nos ayudan a crear una versi�n del libro bien
formateado de una manera consistente. Las reglas de revisi�n que sigamos
est�n dise�adas para ayudarnos a lograr ese resultado. Por favor, lea
cuidadosamente el resto de las Reglas de revisi�n, teniendo esto en
cuenta. Existe otro grupo de Reglas de formato aparte. Estas reglas de
revisi�n son designadas s�lo para la revisi�n. Un segundo equipo de
voluntarios trabajar� formateando el texto.
</p>
<p>
Para ayudar al pr�ximo revisor y las personas que hacen el formato y el pos-proceso conservamos
<a href="#line_br">las quiebras de las l�neas</a>. Esto hace m�s f�cil comparar el
texto con la imagen original.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Summary Guidelines">
  <tbody>
    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>

</table>



<h3><a name="about">Acerca de este documento</a></h3>
<p>
La finalidad de este documento es de explicar las reglas que utilizamos
para mantener la coherencia al revisar un libro compartido por muchos
revisores, de los que cada uno trabaja en p�ginas diferentes. Esto
asegura que todos estemos revisando <em>de la misma manera</em> y hace m�s
f�cil el trabajo del formateador y el del pos-procesador que junta todas
las p�ginas en un e-book (libro electr�nico) completo.
</p>
<p>
<i>Este documento de ninguna manera pretende ser modelo para la edici�n de libros.</i><br>
</p>
<p>
Hemos incluido en este documento todos los detalles que los nuevos
revisores han ido preguntado en sus comienzos. Si hay cosas que faltan,
o cree que algo se debe hacer de otra manera, o si algo no est� bien
claro, av�senos por favor.
</p>
<p>
Este documento est� evolucionando en el tiempo. Ay�denos a mejorar
escribiendo sus sugerencias en el Foro de Documentaci�n en <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=18057"> este enlace</a>.
</p>

<h3><a name="comments">Comentarios del proyecto</a></h3>

<p>En la p�gina de interfaz (Project Page) con la que empieza la revisi�n,
hay una parte que se llama �Project Comments� (comentarios del proyecto)
la que contiene a la informaci�n espec�fica del proyecto (libro).
<b>�L�alos antes de empezar a revisar p�ginas!</b> Si el gerente del
proyecto (Project Manager) quiere que en el libro concreto se haga algo
diferente de la manera que est� descrita en estas Reglas de Revisi�n,
eso ser� aclarado all�. Las instrucciones en los Project Comments
''anulan'' las de las Reglas de Revisi�n, por lo tanto s�galas. Tambi�n
puede haber instrucciones en los Project Comments que se refieran al
formato, y no se utilizan durante la primera fase de revisi�n. En estos
comentarios es donde el Project Manager le puede dar todo tipo de
informaci�n o curiosidades sobre el autor y el proyecto.
</p>
<p>
<em>Por favor, lea tambi�n el foro del proyecto (Project Thread Forum)</em>: All�
es donde el Project Manager puede aclarar las reglas de revisi�n
espec�ficas al proyecto y los revisores suelen usarlo para avisar a
otros revisores sobre problemas que se repiten dentro del mismo y c�mo
manejarlos (Vea abajo).
</p>
<p>En la Project Page (P�gina del Proyecto), el enlace 'Images, Pages
Proofread, & Differences' (Im�genes, P�ginas Revisadas y Diferencias) le
permite ver como otros revisores han hecho los cambios. <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=10217"> En este foro</a> se
habla sobre las diferentes maneras de usar esta informaci�n.
</p>

<h3><a name="forums">Foro/Discusi�n sobre este proyecto</a></h3>
<p>En la p�gina de interfaz (Project Page), donde empieza la revisi�n de
las p�ginas, en la l�nea �Forum� (Foro), hay un enlace titulado �Discuss
this Project� (Discutir este proyecto) si ya se ha iniciado la
discusi�n, o �Start a discussion on this Project� (Empezar la discusi�n
sobre este proyecto) si todav�a no ha empezado. Haciendo clic en ese
enlace ir� a un foro dedicado al proyecto en concreto. Ese es el lugar
para hacer preguntas sobre el libro, avisar al gerente del proyecto
(Project Manager) sobre problemas, etc. Se sugiere utilizar este foro
para comunicarse con el gerente del proyecto y otros revisores que
trabajan en el mismo.
</p>

<h3><a name="prev_pg">Corrigiendo errores en anteriores p�ginas</a></h3>
<p>Cuando selecciona un proyecto para revisar, se carga la P�gina del
Proyecto (Project Page). Esta p�gina contiene enlaces a p�ginas del
proyecto que Ud. ha revisado �ltimamente. Si a�n no ha revisado ninguna
p�gina, no se muestra ning�n enlace.
</p>

<p>Las p�ginas que aparecen debajo de �DONE� (terminado) o �IN PROGRESS�
(en progreso) est�n todav�a disponibles para que Ud. las corrija o
termine su revisi�n. S�lo haga clic en el enlace de la p�gina. Si
descubre que se ha equivocado en alguna p�gina, o ha marcado algo
incorrectamente, puede hacer clic en cualquiera de ellas para reabrirla
y corregir el error.
</p>
<p>Puede tambi�n utilizar los enlaces �Images, Pages Proofread, &
Differences� (Im�genes, P�ginas ya Revisadas y Diferencias) � �Just My
Pages� (S�lo mis P�ginas) en la P�gina del Proyecto. Estas p�ginas le
mostrar�n el enlace �Edit� al lado de todas aquellas en las que Ud. ha
trabajado en la presente ronda y que todav�a pueden ser corregidas.
</p>
<p>Para m�s informaci�n, vea la
<a href="prooffacehelp.php?i_type=0">Standard
Proofreading Interface Help</a> �
<a href="prooffacehelp.php?i_type=1"> Enhanced
Proofreading Interface Help</a> dependiendo de la interfaz que utilice.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" cellpadding="6" summary="Title Page">
  <tbody>
    <tr>
      <td bgcolor="silver"><font size="+2">C&oacute;mo revisar...</font></td>
    </tr>
  </tbody>
</table>

<h3><a name="line_br">Saltos de l�nea</a></h3>

<p>Conservamos los saltos de l�nea. <b>Deje todos los saltos de l�nea donde
est�n</b> para que m�s adelante otros voluntarios puedan comparar f�cilmente
las l�neas del texto con las de la imagen.
</p>


<!-- END RR -->
<!-- We should have an example right here for this. -->

<h3><a name="double_q">Comillas dobles</a></h3>
<p>Revise las comillas dobles as�: ". No las cambie a comillas simples.
D�jelas como las escribi� el autor.
</p>
<p>Para otros idiomas que no sea el ingl�s, utilice las comillas
caracter�sticas para el idioma en concreto, si est�n disponibles en la
lista de caracteres de Latin-1. Guillemets franceses (<tt>&laquo;estas&raquo;</tt>) est�n
disponibles en los men�s desplegables de la p�gina de interfaz de
revisores porque forman una parte de Latin-1. Las comillas alemanas
(&bdquo;estas&rdquo;) no est�n disponibles all� porque no forman parte de Latin-1.
Puede que el Project Manager le informe en los <a href="#comments">Comentarios del
proyecto</a> de la pauta a seguir en un proyecto
en concreto.
</p>

<h3><a name="single_q">Comillas simples</a></h3>
<p>Rev�selas como ASCII normal (as�: <tt>'</tt> ), comilla simple (ap�strofe). No
cambie las comillas simples a dobles. D�jelas como el autor las
escribi�.
</p>

<h3><a name="quote_ea">Comillas en cada l�nea</a></h3>

<p>Los p�rrafos que tienen las comillas al principio de cada l�nea se
corrigen dejando <b>solamente</b> las del principio del p�rrafo.
</p>
<p>Si la cita se prolonga a lo largo de varios p�rrafos, cada uno de ellos
debe retener solamente las comillas de la primera l�nea del mismo.
</p>
<p>A veces no hay comillas que cierran la cita hasta el final de la misma,
que puede estar en otra p�gina. D�jelo como est�&mdash;no ponga comillas que
no se ven en la imagen.
</p>

<h3><a name="period_s">Punto final de las frases</a></h3>
<p>Deje s�lo un espacio despu�s del punto final de la frase.
</p>
<p>Si en el texto escaneado aparecen varios espacios despu�s del punto
final, no es necesario quitar los espacios sobrantes&mdash;eso se hace
autom�ticamente durante el pos-proceso. Vea las <a href="#para_side">Notas al margen</a> para un ejemplo.

<h3><a name="punctuat">Puntuaci�n</a></h3>
<p>En general, no deber�a de haber espacios en blanco ante los caracteres
de puntuaci�n excepto ante las comillas que abren la frase. Si hay un
espacio en blanco dejado por el OCR, qu�telo. Esto se refiere incluso a
los idiomas como el franc�s, los que normalmente dejan un espacio en
blanco ante los caracteres de puntuaci�n.
</p>
<p>Este espacio en blanco a veces aparece en los libros impresos durante los siglos XVIII y XIX ya que en esa �poca era frecuente dejar medio espacio entre la palabra y los dos puntos o el punto y coma.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Punctuation">
  <tbody>

    <tr><th align="left" bgcolor="cornsilk">Texto escaneado:</th></tr>
    <tr>
      <td valign="top">and so it goes&nbsp;; ever and ever.</td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto correctamente revisado:</th></tr>
    <tr>
      <td valign="top"><tt>and so it goes; ever and ever.</tt></td>

    </tr>
  </tbody>
</table>

<h3><a name="period_p">Puntos suspensivos "..."</a></h3>
<p>Las reglas son diferentes para ingl�s y otros idiomas (LOTE).
</p>
<p><b>Ingl�s</b>:
 Deje un espacio antes y despu�s de los puntos suspensivos. La excepci�n es cuando los puntos suspensivos se encuentren al final de la frase donde no habr� espacio en blanco antes, sino cuatro puntos y un espacio despu�s.... Lo mismo ocurre con cualquier otro signo de puntuaci�n: los puntos suspensivos siguen al signo que los precede sin ning�n espacio entre ellos.
</p>
<p>For example:<br>

   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is true.
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the end....
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?...
   </tt>
</p>
<p>A veces encontrar�n el signo de puntuaci�n al final; como en el ejemplo:
<br>
   <tt>
   <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo...?
   </tt>
</p>
<p>Elimine si sobran puntos o a�ada los que sean necesarios para conseguir el formato correcto.
</p>

<p><b>LOTE:</b> (Languages Other Than English--otros idiomas que no sean ingl�s.)
Use la regla principal: &laquo;siga lo m�s exactamente posible el estilo de la p�gina
impresa&raquo;. Inserte espacio en blanco si es necesario antes o entre los puntos
suspensivos, y use el mismo n�mero de puntos que aparece en la imagen de
la p�gina impresa. A veces la imagen no se ve bien.
En ese caso, deje una nota para llamar atenci�n del pos-procesador as�: <tt>[**unclear]</tt>.
</p>

<h3><a name="contract">Contracciones</a></h3>
<p>Elimine espacio en blanco de las contracciones. Por ejemplo:
<tt>would&nbsp;n't</tt>
se revisa como <tt>wouldn't</tt>. <br>
</p>
<p>El espacio en blanco es com�n en los libros de las �pocas anteriores y
se dejaba para indicar que originalmente se trata de dos palabras
separadas, 'would' y 'not.' A veces lo deja el OCR sin raz�n aparente.
En todo caso elimine este espacio en blanco.
</p>
<p>Algunos gerentes de proyecto (Project Managers) le dir�n en los
<a href="#comments">Comentarios del proyecto</a> que no elimine
estos espacios en blanco de las contracciones, especialmente si se trata
de los textos que contienen argot, dialectos o son textos en idiomas
distintos al ingl�s.
</p>


<h3><a name="extra_sp">Varios espacios en blanco entre palabras</a></h3>
<p>Las tabulaciones o varios espacios en blanco entre palabras aparecen a
menudo en los resultados de OCR. No se moleste en eliminarlos&mdash;eso se
har� autom�ticamente en el pos-proceso.
</p>
<p>En cambio, <b>s�</b> es necesario eliminar estos espacios entre las palabras y
signos de puntuaci�n (comillas, puntos, punto y coma, etc.).
</p>
<p>
Por ejemplo: <b>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse.</b> el espacio entre
la palabra 'horse' y el punto y coma hay que eliminar. Pero, los dos
espacios despu�s del punto y coma se pueden dejar&mdash;no es necesario
eliminar uno de ellos.
</p>

<h3><a name="trail_s">Espacios en blanco al final de la l�nea</a></h3>
<p>No pierda tiempo en insertar espacios en blanco al final de las l�neas
del texto. Es algo que se hace autom�ticamente m�s adelante en el
pos-proceso. Tampoco pierda tiempo en eliminar los que sobran al final
de las l�neas.
</p>

<h3><a name="line_no">N�meros de l�nea</a></h3>

<p>Si las l�neas tienen n�meros, cons�rvelos. Col�quelos al menos seis
espacios a la derecha del resto del texto, aun cuando originalmente se
encuentran a la izquierda de la poes�a/texto original.
</p>
<p>N�meros de l�nea se encuentran al margen de cada l�nea, o a veces cada
cinco o diez l�neas. Son comunes en libros de poes�a. Como el libro
electr�nico (e-book) no tiene p�ginas, los n�meros de los versos sirven
para orientar al lector.
</p>
<!-- END RR -->
<!-- We need an example image and text for this. -->

<h3><a name="italics">Textos en cursiva y negrita</a></h3>
<p><i>Textos en cursiva</i> a veces aparecen entre <tt>&lt;i&gt;</tt>y<tt>&lt;/i&gt;</tt>. <b>Texto en negrita</b> a
veces aparece entre <tt>&lt;b&gt;</tt>y<tt>&lt;/b&gt;</tt>. No elimine esta informaci�n de formato, a menos
que <b>no</b> aparezca en la imagen. No agregue este formato en esta
ronda, las personas que dan formato al texto (los formateadores) lo har�n
m�s adelante.
</p>
<!-- END RR -->


<h3><a name="supers">Super�ndice</a></h3>
<p>En los libros antiguos eran frecuentes las contracciones que se
imprim�an en forma de super�ndices.
<br>
Por ejemplo:
<br>Gen<sup>rl</sup> Washington defeated L<sup>d</sup>  Cornwall's army.
Aparecen en muchos libros en castellano y franc�s: S<sup>r</sup> , S<sup>ra</sup>, M<sup>lle</sup>, etc.
<br>
Revise estas contracciones insertando un acento circunflejo (car�cter ^) para identificar que
lo que sigue es texto en super�ndice.
<br>
As�:<br>
&nbsp;&nbsp;&nbsp;&nbsp; <tt>Gen^rl Washington defeated L^d Cornwall's army.
Y: S^r,S^ra, M^lle, etc.</tt>
</p>


<h3><a name="subscr">Sub�ndice</a></h3>
<p>El texto en sub�ndice se encuentra en obras cient�ficas pero no es com�n en otro
tipo de obras. Revise el texto subscrito insertando un gui�n bajo (car�cter <tt>_</tt>), as�:
<br>
   <br>Por ejemplo:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; H<sub>2</sub>O.
   <br>se revisa de esta manera:
   <br>&nbsp; &nbsp; &nbsp; &nbsp; <tt>H_2O.<br></tt>

</p>


<h3><a name="font_sz">Cambio de tama�o de fuente</a></h3>
<p>No indique cambios del tama�o de fuente. Las personas que dan el formato al texto (los formateadores) lo har�n m�s adelante.
</p>

<h3><a name="small_caps">Palabras en may�sculas peque�as-versalitas</a></h3>
<p>
Las palabras impresas en letras may�sculas peque�as-versalitas-(<span style=
"font-variant:small-caps">Versalitas</span>)
con letras may�sculas y may�sculas peque�as-versalitas mezcladas, pueden
aparecer entre estos s�mbolos: <tt>&lt;sc&gt;</tt> y <tt>&lt;/sc&gt;</tt>.  Por favor,
no elimine esta informaci�n
de formato, a menos que est� alrededor de algo que no existe en la imagen.  Tampoco
la ponga donde no est� puesta ya.  Las personas que dan formato (los formateadores)
lo har�n mas adelante en el proceso.  Revise s�lo los caracteres mismos, y no se
preocupe por los cambios de fuente. Si todas las letras son MAY�SCULAS, min�sculas
o Mezcladas, d�jelas como est�n.
</p>

<h3><a name="drop_caps">Letras may�sculas ornamentadas al inicio del p�rrafo, frase o secci�n</a></h3>
<p>Revise este tipo de letra ornamentada al inicio de un p�rrafo, frase o
secci�n como letras normales.
</p>


<h3><a name="a_chars">Caracteres acentuados</a></h3>

<p>Por favor, utilice los caracteres de Latin-1 donde sea posible. Vea<a href="#d_chars">
Signos diacr�ticos</a> donde se explica como revisar
caracteres no-Latin-1.
</p>
<p>Si utiliza un teclado que no tiene los caracteres acentuados, existen
varias maneras de hacerlo:
</p>
<ul compact>
  <li> Los men�s desplegables de la p�gina de interfaz de revisi�n.</li>
  <li> Applets incluidos en su sistema de operaci�n.
      <ul compact>
      <li>Windows: �Character Map�<br> Ruta de acceso:<br>
          Haga clic en Start, Run; charmap o<br>
          Haga clic en Start, Accessories, System Tools, Character Map.</li>
      <li>Macintosh: Key Caps o �Keyboard Viewer�<br>
          Para OS 9 y anteriores, est� en Apple Menu,<br>
          Para OS X hasta 10.2, est� en Applications, Utilities folder<br>
          Para OS X 10.3 y siguientes, est� en Input Menu como �Keyboard Viewer.�</li>

      <li>Linux: Varias maneras, depende del escritorio.<br>
          Para KDE, intente KCharSelect (en el submen� Utilities del men� principal).</li>
      </ul>
  </li>
  <li>El programa en l�nea como<a
   href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</a>.</li>
  <li> Teclas personalizadas.<br>

       Tablas para <a href="#a_chars_win">Windows</a> y <a href="#a_chars_mac">Macintosh</a>
donde se muestra los atajos del teclado.</li>
  <li> Cambiar la configuraci�n del teclado para que soporte caracteres acentuados ("deadkeys").
       <ul compact>
       <li>Windows: Panel de Control (Teclado, Input Locales)</li>
       <li>Macintosh: Input Menu (en la Barra del Men�)</li>
       <li>Linux: Modifique el teclado en su configuraci�n X.</li>
      </ul>
</ul>
<p>El Proyecto Gutenberg aceptar� como m�nimo, versiones de textos 7-bit
ASCII, aunque acepta tambi�n otras versiones de codificaci�n, que
conservan m�s informaci�n del texto original. Proyecto Gutemberg-Europa
utiliza UTF-8 como principal codificador, aunque est�n bienvenidas otras
formas de codificaci�n. En este momento Distributed Proofreaders utiliza
Latin-1 o ISO 8859-1 y -15. pero en el futuro se incluir� Unicode.
Distributed Proofreaders Europe ya utiliza Unicode.
</p>

<!-- END RR -->
<a name="a_chars_win"></a>
<p><b>Para Windows</b>:
</p>
<ul compact>
  <li>Puede utilizar el &ldquo;Character Map&rdquo; (Start, Run, charmap) para elegir una letra; y despu�s copiar y pegar.
  </li>

  <li>Men�s desplegables de la interfaz &laquo;Enhanced&raquo; de revisi�n.
  </li>
  <li>O puede obtener los caracteres si oprime �Alt� + N�mero correspondiente del car�cter que necesita.
      <br>
Este modo resulta el m�s r�pido una vez haya aprendido los c�digos correspondientes.<br>
(Estas instrucciones son para el teclado US-ingl�s. Puede que no funcionen con otros teclados.)
      <br>Oprima la tecla &ldquo;Alt&rdquo; y, sin soltarla, teclee las cuatro cifras en
el <i>teclado num�rico</i> y entonces suelte la tecla &ldquo;Alt&rdquo; (l�nea de teclado num�rico sobre las letras no funcionar� para este fin).
      <br>Debe teclear las cuatro cifras, incluyendo el 0 (cero) al principio.
Como se puede dar cuenta, la letra may�scula tiene el c�digo de la min�scula
reducido en 32.
Igualmente existen configuraciones con las que no se puedan utilizar estos c�digos.
      <br>En la siguiente tabla se ven los c�digos utilizados ahora.
          (<a href="charwin.pdf">Tabla en ingl�s que puede imprimir)</a>
      <br>No debe utilizar otros caracteres aunque el gerente del proyecto lo diga
en los comentarios del proyecto<a href="#comments">Comentarios del proyecto</a>.
  </li>
</ul>

<br>
<table align="center" border="6" rules="all" summary="Atajos para Windows">
  <tbody>
  <tr>
      <th bgcolor="cornsilk" colspan=14>Atajos de Windows de los s�mbolos de Latin-1</th>
  </tr>

  <tr bgcolor="cornsilk">
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; di�resis</th>

      <th colspan=2>&deg; aro</th>
      <th colspan=2>&AElig; ligadura</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a a grave"         >&agrave; </td><td>Alt-0224</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a agudo"         >&aacute; </td><td>Alt-0225</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a a circunflejo"    >&acirc;  </td><td>Alt-0226</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a tilde"         >&atilde; </td><td>Alt-0227</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a di�resis"        >&auml;   </td><td>Alt-0228</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a aro"          >&aring;  </td><td>Alt-0229</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a ae ligadura"     >&aelig;  </td><td>Alt-0230</td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A may�scula grave"       >&Agrave; </td><td>Alt-0192</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula agudo"       >&Aacute; </td><td>Alt-0193</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula circunflejo"  >&Acirc;  </td><td>Alt-0194</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula tilde"       >&Atilde; </td><td>Alt-0195</td>

      <td align="center" bgcolor="mistyrose" title="A may�scula di�resis"      >&Auml;   </td><td>Alt-0196</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula aro"        >&Aring;  </td><td>Alt-0197</td>
      <td align="center" bgcolor="mistyrose" title="AE may�scula ligadura"   >&AElig;  </td><td>Alt-0198</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a e grave"         >&egrave; </td><td>Alt-0232</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a e agudo"         >&eacute; </td><td>Alt-0233</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a e circunflejo"    >&ecirc;  </td><td>Alt-0234</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a e di�resis"        >&euml;   </td><td>Alt-0235</td>
      <td> </td><td> </td>

      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E may�scula grave"       >&Egrave; </td><td>Alt-0200</td>
      <td align="center" bgcolor="mistyrose" title="E may�scula agudo"       >&Eacute; </td><td>Alt-0201</td>
      <td align="center" bgcolor="mistyrose" title="E may�scula circunflejo"  >&Ecirc;  </td><td>Alt-0202</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E may�scula di�resis"      >&Euml;   </td><td>Alt-0203</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="Peque�a i grave"         >&igrave; </td><td>Alt-0236</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i agudo"         >&iacute; </td><td>Alt-0237</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i circunflejo"    >&icirc;  </td><td>Alt-0238</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i di�resis"        >&iuml;   </td><td>Alt-0239</td>

      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I may�scula grave"       >&Igrave; </td><td>Alt-0204</td>
      <td align="center" bgcolor="mistyrose" title="I may�scula agudo"       >&Iacute; </td><td>Alt-0205</td>

      <td align="center" bgcolor="mistyrose" title="I may�scula circunflejo"  >&Icirc;  </td><td>Alt-0206</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I may�scula di�resis"      >&Iuml;   </td><td>Alt-0207</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligadura</th>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a o grave"         >&ograve; </td><td>Alt-0242</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o agudo"         >&oacute; </td><td>Alt-0243</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o circunflejo"    >&ocirc;  </td><td>Alt-0244</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o tilde"         >&otilde; </td><td>Alt-0245</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a o di�resis"        >&ouml;   </td><td>Alt-0246</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o barra"         >&oslash; </td><td>Alt-0248</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a oe ligadura"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O may�scula grave"       >&Ograve; </td><td>Alt-0210</td>

      <td align="center" bgcolor="mistyrose" title="O may�scula agudo"       >&Oacute; </td><td>Alt-0211</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula circunflejo"  >&Ocirc;  </td><td>Alt-0212</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula tilde"       >&Otilde; </td><td>Alt-0213</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula di�resis"      >&Ouml;   </td><td>Alt-0214</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula barra"       >&Oslash; </td><td>Alt-0216</td>

      <td align="center" bgcolor="mistyrose" title="O may�sculaE ligadura"   >&OElig;  </td><td>Use [OE]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a u grave"         >&ugrave; </td><td>Alt-0249</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u agudo"         >&uacute; </td><td>Alt-0250</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u circunflejo"    >&ucirc;  </td><td>Alt-0251</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u di�resis"        >&uuml;   </td><td>Alt-0252</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="U may�scula grave"       >&Ugrave; </td><td>Alt-0217</td>
      <td align="center" bgcolor="mistyrose" title="U may�scula agudo"       >&Uacute; </td><td>Alt-0218</td>
      <td align="center" bgcolor="mistyrose" title="U may�scula circunflejo"  >&Ucirc;  </td><td>Alt-0219</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U may�scula di�resis"      >&Uuml;   </td><td>Alt-0220</td>

      <th colspan=2 bgcolor="cornsilk">dinero     </th>
      <th colspan=2 bgcolor="cornsilk">matem�tica  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Peque�a n tilde"         >&ntilde; </td><td>Alt-0241</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a y di�resis"        >&yuml;   </td><td>Alt-0255</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Alt-0162</td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Alt-0177</td>
  </tr>

  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N may�scula tilde"       >&Ntilde; </td><td>Alt-0209</td>
      <td align="center" bgcolor="mistyrose" title="Y may�scula di�resis"      >&Yuml;   </td><td>Alt-0159</td>

      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Alt-0163</td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>Alt-0215</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">Island�s    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>

      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuaci�n  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Alt-0165</td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Alt-0247</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a c cedilla"       >&ccedil; </td><td>Alt-0231</td>

      <td align="center" bgcolor="mistyrose" title="May�scula Thorn"         >&THORN;  </td><td>Alt-0222</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Alt-0169</td>
      <td align="center" bgcolor="mistyrose" title="acento agudo"          >&acute;  </td><td>Alt-0180</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Alt-0191</td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>Alt-0036</td>

      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Alt-0172</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="May�scula C cedilla"     >&Ccedil; </td><td>Alt-0199</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a thorn"           >&thorn;  </td><td>Alt-0254</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Alt-0174</td>

      <td align="center" bgcolor="mistyrose" title="di�resis accent"         >&uml;    </td><td>Alt-0168</td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Alt-0161</td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Alt-0164</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Alt-0176</td>
  </tr>

  <tr><th colspan=2 bgcolor="cornsilk">super�ndices        </th>
      <td align="center" bgcolor="mistyrose" title="Eth may�scula"           >&ETH;    </td><td>Alt-0208</td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Alt-0153</td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Alt-0175</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Alt-0171</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Alt-0181</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 1"         >&sup1;   </td><td>Alt-0185</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a eth"             >&eth;    </td><td>Alt-0240</td>

      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Alt-0182</td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Alt-0184</td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Alt-0187</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; <sup><small>1</small></sup></td><td>Alt-0188</td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 2"         >&sup2;   </td><td>Alt-0178</td>
      <th colspan=2 bgcolor="cornsilk">sz ligadura        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Alt-0167</td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Alt-0183</td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Alt-0186</td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; <sup><small>1</small></sup></td><td>Alt-0189</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 3"         >&sup3;   </td><td>Alt-0179</td>

      <td align="center" bgcolor="mistyrose" title="sz ligadura"           >&szlig;  </td><td>Alt-0223</td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>Alt-0166</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>Alt-0042</td>
      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Alt-0170</td>

      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; <sup><small>1</small></sup></td><td>Alt-0190</td>
  </tr>
  </tbody>
</table>
<p>
Note: en la mayor�a de los casos las dos letras individuales (o y e) han reemplazado las ligaduras. (&OElig; se vuelve en Oedipus.) Eche un vistazo a los Comentarios del proyecto para ver si el gerente de proyecto quiere utilizar la ligadura; si no, utilice las dos letras.<br>
<sup><small>1</small></sup>Note: Si no est� escrito especialmente en los
Comentarios del proyecto, no utilice los atajos (�Alt� y n�mero) s�mbolos para
fracciones. En vez de eso haga los fracciones como dice las reglas de revisi�n
en cuanto a los fracciones (&frac12;, &frac14;, etc.).<a href="#fract_s">Fracciones</a><br>


<p> <b>Para Apple Macintosh</b>:
</p>
<ul compact>
  <li>Puede utilizar el programa �Key Caps� como referencia.<br>
En OS 9 y anteriores, est� en el men� de Apple; OS X hasta 10.2 est� en �Applications, Utilities.�<br>
Se visualiza imagen del teclado y si oprime �shift, opt, command� o combinaci�n de esas teclas, le muestra c�mo crear cada car�cter. Utilice esta referencia para ver c�mo teclear el car�cter deseado, o puede copiar desde aqu� y pegar al texto de la interfaz de revisi�n.
</li>

  <li>En OS X 10.3 y siguientes, la misma funci�n est� ahora en una paleta disponible en el men� �Input� (el men� junto a �locale's flag icon� en la barra del men�).  Se llama �Show Keyboard Viewer.� Si no est� en su men� de �Input� o no tiene ese men� puede activarlo abriendo �System Preferences,� el �International panel� y elegir �Input Menu.�  El �Show input menu in menu bar� debe estar marcado.  En la vista del �spreadsheet� marque la casilla de �Keyboard Viewer� en adici�n a cualquier �locales� que utilice.
En OS X 10.3 y siguientes, la misma funci�n est� ahora en una paleta disponible en el men� �Input� (el men� junto a �locale's flag icon� en la barra del men�).  Se llama �Show Keyboard Viewer.� Si no est� en su men� de �Input� o no tiene ese men� puede activarlo abriendo �System Preferences,� el �International panel� y elegir �Input Menu.�  El �Show input menu in menu bar� debe estar marcado.  En la vista del �spreadsheet� marque la casilla de �Keyboard Viewer� en adici�n a cualquier �locales� que utilice.<br>
  </li>
  <li>
Si trabaja con la interfaz mejorada �enhanced�, haga clic en &ldquo;more&rdquo; y se abrir� un men� desplegable mostrando las letras que se puedan copiar y pegar.
</li>
<li>O se pueden teclear los atajos de Apple Opt de los caracteres deseados.

      <br>Una vez acostumbrado a los c�digos es la manera m�s r�pida que copiar y pegar.
      <br>Oprima la tecla �Opt� y el acento; despu�s teclee la letra que quiere acentuar (para algunos c�digos, basta con oprimir �Opt� y la letra).
      <br>Estas instrucciones son para el teclado US-ingl�s. Puede que no funcionen con otros teclados.
      <br>La tabla siguiente muestra los c�digos que utilizamos.
          (<a href="charapp.pdf">Una versi�n en ingl�s para imprimir.)</a>

      <br>Note: Por favor no utilice otros caracteres �especiales� aunque el gerente del proyecto lo diga en los Comentarios del proyecto. <a href="#comments">Comentarios del proyecto</a>.
  </li>
</ul>

<br>
<a name="a_chars_mac"></a>
<table align="center" border="6" rules="all" summary="Mac shortcuts">
  <tbody>
  <tr bgcolor="cornsilk"  >
      <th colspan=14>Atajos para los s�mbolos de Latin-1 para Apple Mac</th>

  <tr bgcolor="cornsilk"  >
      <th colspan=2>` grave</th>
      <th colspan=2>&acute; agudo (aigu)</th>
      <th colspan=2>^ circunflejo</th>
      <th colspan=2>~ tilde</th>
      <th colspan=2>&uml; di�resis</th>

      <th colspan=2>&deg; aro</th>
      <th colspan=2>&AElig; ligadura</th>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a a grave"         >&agrave; </td><td>Opt-`, a</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a agudo"         >&aacute; </td><td>Opt-e, a</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a a circunflejo"    >&acirc;  </td><td>Opt-i, a</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a tilde"         >&atilde; </td><td>Opt-n, a</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a di�resis"        >&auml;   </td><td>Opt-u, a</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a a aro"          >&aring;  </td><td>Opt-a   </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a ae ligadura"     >&aelig;  </td><td>Opt-'   </td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="A may�scula grave"       >&Agrave; </td><td>Opt-~, A</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula agudo"       >&Aacute; </td><td>Opt-e, A</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula circunflejo"  >&Acirc;  </td><td>Opt-i, A</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula tilde"       >&Atilde; </td><td>Opt-n, A</td>

      <td align="center" bgcolor="mistyrose" title="A may�scula di�resis"      >&Auml;   </td><td>Opt-u, A</td>
      <td align="center" bgcolor="mistyrose" title="A may�scula aro"        >&Aring;  </td><td>Opt-A   </td>
      <td align="center" bgcolor="mistyrose" title="A may�sculaE ligadura"   >&AElig;  </td><td>Opt-"   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a e grave"         >&egrave; </td><td>Opt-~, e</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a e agudo"         >&eacute; </td><td>Opt-e, e</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a e circunflejo"    >&ecirc;  </td><td>Opt-i, e</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a e di�resis"        >&euml;   </td><td>Opt-u, e</td>
      <td> </td><td> </td>

      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="E may�scula grave"       >&Egrave; </td><td>Opt-~, E</td>
      <td align="center" bgcolor="mistyrose" title="E may�scula agudo"       >&Eacute; </td><td>Opt-e, E</td>
      <td align="center" bgcolor="mistyrose" title="E may�scula circunflejo"  >&Ecirc;  </td><td>Opt-i, E</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="E may�scula di�resis"      >&Euml;   </td><td>Opt-u, E</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="Peque�a i grave"         >&igrave; </td><td>Opt-~, i</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i agudo"         >&iacute; </td><td>Opt-e, i</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i circunflejo"    >&icirc;  </td><td>Opt-i, i</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a i di�resis"        >&iuml;   </td><td>Opt-u, i</td>

      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="I may�scula grave"       >&Igrave; </td><td>Opt-~, I</td>
      <td align="center" bgcolor="mistyrose" title="I may�scula agudo"       >&Iacute; </td><td>Opt-e, I</td>

      <td align="center" bgcolor="mistyrose" title="I may�scula circunflejo"  >&Icirc;  </td><td>Opt-i, I</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="I may�scula di�resis"      >&Iuml;   </td><td>Opt-u, I</td>
      <th colspan=2 bgcolor="cornsilk">/ barra</th>
      <th colspan=2 bgcolor="cornsilk">&OElig; ligadura</th>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a o grave"         >&ograve; </td><td>Opt-~, o</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o agudo"         >&oacute; </td><td>Opt-e, o</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o circunflejo"    >&ocirc;  </td><td>Opt-i, o</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o tilde"         >&otilde; </td><td>Opt-n, o</td>

      <td align="center" bgcolor="mistyrose" title="Peque�a o di�resis"        >&ouml;   </td><td>Opt-u, o</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a o barra"         >&oslash; </td><td>Opt-o   </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a oe ligadura"     >&oelig;  </td><td>Use [oe]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="O may�scula grave"       >&Ograve; </td><td>Opt-~, O</td>

      <td align="center" bgcolor="mistyrose" title="O may�scula agudo"       >&Oacute; </td><td>Opt-e, O</td>
      <td align="center" bgcolor="mistyrose" title="I may�scula circunflejo"  >&Ocirc;  </td><td>Opt-i, O</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula tilde"       >&Otilde; </td><td>Opt-n, O</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula di�resis"      >&Ouml;   </td><td>Opt-u, O</td>
      <td align="center" bgcolor="mistyrose" title="O may�scula barra"       >&Oslash; </td><td>Opt-O   </td>

      <td align="center" bgcolor="mistyrose" title="O may�sculaE ligadura"   >&OElig;  </td><td>Use [OE]</td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a u grave"         >&ugrave; </td><td>Opt-~, u</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u agudo"         >&uacute; </td><td>Opt-e, u</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u circunflejo"    >&ucirc;  </td><td>Opt-i, u</td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a u di�resis"        >&uuml;   </td><td>Opt-u, u</td>
      <td> </td><td> </td>
      <td> </td><td> </td>
  </tr>

  <tr><td align="center" bgcolor="mistyrose" title="U may�scula grave"       >&Ugrave; </td><td>Opt-~, U</td>
      <td align="center" bgcolor="mistyrose" title="U may�scula agudo"       >&Uacute; </td><td>Opt-e, U</td>
      <td align="center" bgcolor="mistyrose" title="U may�scula circunflejo"  >&Ucirc;  </td><td>Opt-i, U</td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="U may�scula di�resis"      >&Uuml;   </td><td>Opt-u, U</td>

      <th colspan=2 bgcolor="cornsilk">dinero     </th>
      <th colspan=2 bgcolor="cornsilk">matem�tica  </th>
  </tr>
  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Peque�a n tilde"         >&ntilde; </td><td>Opt-n, n</td>
      <td align="center" bgcolor="mistyrose" title="Peque�a y di�resis"        >&yuml;   </td><td>Opt-u, y</td>
      <td align="center" bgcolor="mistyrose" title="Cents"                 >&cent;   </td><td>Opt-4   </td>
      <td align="center" bgcolor="mistyrose" title="plus/minus"            >&plusmn; </td><td>Opt-+   </td>
  </tr>

  <tr><td> </td><td> </td>
      <td> </td><td> </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="N may�scula tilde"       >&Ntilde; </td><td>Opt-n, N</td>
      <td align="center" bgcolor="mistyrose" title="Capital Y di�resis"      >&Yuml;   </td><td>Opt-u, Y</td>

      <td align="center" bgcolor="mistyrose" title="Pounds"                >&pound;  </td><td>Opt-3   </td>
      <td align="center" bgcolor="mistyrose" title="Multiplication"        >&times;  </td><td>(none)&nbsp;&dagger;</td>
  </tr>
  <tr><th colspan=2 bgcolor="cornsilk">cedilla </th>
      <th colspan=2 bgcolor="cornsilk">Island�s    </th>
      <th colspan=2 bgcolor="cornsilk">marcas        </th>

      <th colspan=2 bgcolor="cornsilk">acentos      </th>
      <th colspan=2 bgcolor="cornsilk">puntuaci�n  </th>
      <td align="center" bgcolor="mistyrose" title="Yen"                   >&yen;    </td><td>Opt-y   </td>
      <td align="center" bgcolor="mistyrose" title="Division"              >&divide; </td><td>Opt-/   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Peque�a c cedilla"       >&ccedil; </td><td>Opt-c   </td>

      <td align="center" bgcolor="mistyrose" title="Capital Thorn"         >&THORN;  </td><td>(none)&nbsp;&Dagger;</td>
      <td align="center" bgcolor="mistyrose" title="Copyright"             >&copy;   </td><td>Opt-g   </td>
      <td align="center" bgcolor="mistyrose" title="acento agudo"          >&acute;  </td><td>Opt-E   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Question Mark">&iquest; </td><td>Opt-?   </td>
      <td align="center" bgcolor="mistyrose" title="Dollars"               >&#036;   </td><td>Shift-4</td>

      <td align="center" bgcolor="mistyrose" title="Logical Not"           >&not;    </td><td>Opt-l   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="Capital C cedilla"     >&Ccedil; </td><td>Opt-C   </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a thorn"           >&thorn;  </td><td>Shift-Opt-6</td>
      <td align="center" bgcolor="mistyrose" title="Registration Mark"     >&reg;    </td><td>Opt-r   </td>

      <td align="center" bgcolor="mistyrose" title="di�resis"         >&uml;    </td><td>Opt-U   </td>
      <td align="center" bgcolor="mistyrose" title="Inverted Exclamation"  >&iexcl;  </td><td>Opt-1   </td>
      <td align="center" bgcolor="mistyrose" title="General Currency"      >&curren; </td><td>Shift-Opt-2</td>
      <td align="center" bgcolor="mistyrose" title="Degrees"               >&deg;    </td><td>Opt-*   </td>
  </tr>

  <tr><th colspan=2 bgcolor="cornsilk">super�ndices        </th>
      <td align="center" bgcolor="mistyrose" title="E may�sculath"           >&ETH;    </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Trademark"             >&trade;  </td><td>Opt-2   </td>
      <td align="center" bgcolor="mistyrose" title="macron accent"         >&macr;   </td><td>Shift-Opt-,</td>
      <td align="center" bgcolor="mistyrose" title="guillemot left"        >&laquo;  </td><td>Opt-\   </td>

      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="Micro"                 >&micro;  </td><td>Opt-m   </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 1"         >&sup1;   </td><td>(none)&nbsp;&Dagger;  </td>
      <td align="center" bgcolor="mistyrose" title="Peque�a eth"             >&eth;    </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="Paragraph (pilcrow)"   >&para;   </td><td>Opt-7   </td>
      <td align="center" bgcolor="mistyrose" title="cedilla"               >&cedil;  </td><td>Opt-Z   </td>
      <td align="center" bgcolor="mistyrose" title="guillemot right"       >&raquo;  </td><td>Shift-Opt-\</td>
      <th colspan=2 bgcolor="cornsilk">ordinals  </th>
      <td align="center" bgcolor="mistyrose" title="1/4 Fraction"          >&frac14; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>

  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 2"         >&sup2;   </td><td>(none)&nbsp;&Dagger;  </td>
      <th colspan=2 bgcolor="cornsilk">sz ligadura        </th>
      <td align="center" bgcolor="mistyrose" title="Section"               >&sect;   </td><td>Opt-6   </td>
      <td> </td><td> </td>

      <td align="center" bgcolor="mistyrose" title="Middle dot"            >&middot; </td><td>Opt-8  </td>
      <td align="center" bgcolor="mistyrose" title="Masculine Ordinal"     >&ordm;   </td><td>Opt-0   </td>
      <td align="center" bgcolor="mistyrose" title="1/2 Fraction"          >&frac12; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  <tr><td align="center" bgcolor="mistyrose" title="super�ndice 3"         >&sup3;   </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="sz ligadura"           >&szlig;  </td><td>Opt-s   </td>
      <td align="center" bgcolor="mistyrose" title="Broken Vertical bar"   >&brvbar; </td><td>(none)&nbsp;&Dagger;  </td>
      <td> </td><td> </td>
      <td align="center" bgcolor="mistyrose" title="asterisk"              >&#042;   </td><td>(none)&nbsp;&Dagger;  </td>

      <td align="center" bgcolor="mistyrose" title="Feminine Ordinal"      >&ordf;   </td><td>Opt-9   </td>
      <td align="center" bgcolor="mistyrose" title="3/4 Fraction"          >&frac34; </td><td>(none)&nbsp;&Dagger;<sup><small>1</small></sup>  </td>
  </tr>
  </tbody>
</table>
<p>&Dagger;&nbsp;Note: No hay equivalente; utilice los men�s desplegables.
</p>

<p><sup><small>1</small></sup>Note: Si no est� escrito especialmente en los Comentarios del proyecto, no utilice los atajos ("Alt" y n�mero) s�mbolos para fracciones. En vez de eso haga los fracciones como dice las reglas de revisi�n en cuanto a los fracciones (&frac12;, &frac14;, etc.):
<a href="#fract_s">Fracciones</a>. (1/2, 1/4, 3/4, etc.)
</p>

<h3><a name="d_chars">Signos diacr�ticos</a></h3>
<p>
En algunos proyectos se encontrar�n signos especiales ubicados encima o
debajo del car�cter lat�n normal (A-Z).  Estos signos se llaman
<i>diacr�ticos</i>. Indican la variaci�n del valor fon�tico de la letra.
Cuando revisamos podemos indicarlos dentro del sistema ASCII normal utilizando
unos c�digos especiales.  Por ejemplo, &#259; se indica como <tt>[)a]</tt>... el acento en
forma de u encima del car�cter. Para un acento en forma de u debajo del car�cter
se indica as�: <tt>[a)]</tt>
<p>No olvide incluir los par�ntesis as�: (<tt>[&nbsp;]</tt>) para que el pos-procesador
sepa a qu�
letra se refiere el signo.</p><p>
El pos-procesador eventualmente reemplazar� estos signos con s�mbolos
que funcionen en el texto que est� procesando, como 7-bit, ASCII, 8-bit,
 Unicode, html, etc.</p>

<p>
F�jese que hay algunos signos diacr�ticos (la mayor�a vocales) que ya se
encuentran entre los caracteres Latin-1 que utilizamos normalmente.
<b>En este caso, utilice el car�cter Latin-1 disponible en el men� de la interfaz
del proyecto</b> (vea <a href="#a_chars">aqu�</a>).
</p>

<!-- END RR -->

<p>En la siguiente tabla se ven los c�digos utilizados ahora:<br>
El �x" representa cualquier car�cter con signo diacr�tico.<br>
(Cuando revise utilice el car�cter del texto que est� revisando, y no la �x" mostrada en la tabla.)
</p>

<!--
  diacritical mark           above  below
macron (straight line)       [=x]   [x=]
2 dots (diaresis or umlaut)  [:x]   [x:]
1 dot                        [.x]   [x.]
grave accent                 ['x]   [x'] or [/x] [x/]
acute (aigu) accent          [`x]   [x`] or [\x] [x\]
circumflex                   [^x]   [x^]
caron (v-shaped symbol)      [vx]   [xv]
breve (u-shaped symbol)      [)x]   [x)]
tilde                        [~x]   [x~]
cedilla                      [,x]   [x,]
-->

<table align="center" border="6" rules="all" summary="Diacriticals">
  <tbody>
  <tr bgcolor="cornsilk">
      <th colspan=4>S&iacute;mbolos de Revisi&oacute;n para Signos Diacr&iacute;ticos</th>

  <tr bgcolor="cornsilk">
      <th>signo diacr&iacute;tico</th>
      <th>ejemplo</th>
      <th>encima</th>
      <th>debajo</th>
   </tr>
  <tr><td>macron (l�nea recta)</td>

      <td align="center">&macr;</td>
      <td align="center"><tt>[=x]</tt></td>
      <td align="center"><tt>[x=]</tt></td>
      </tr>
  <tr><td>2 puntos (di�resis/umlaut)</td>
      <td align="center">&uml;</td>
      <td align="center"><tt>[:x]</tt></td>

      <td align="center"><tt>[x:]</tt></td>
      </tr>
  <tr><td>1 punto</td>
      <td align="center">&middot;</td>
      <td align="center"><tt>[.x]</tt></td>
      <td align="center"><tt>[x.]</tt></td>
      </tr>

  <tr><td>acento grave</td>
      <td align="center">`</td>
      <td align="center"><tt>[`x]</tt> or <tt>[\x]</tt></td>
      <td align="center"><tt>[x`]</tt> or <tt>[x\]</tt></td>

      </tr>
  <tr><td>acento agudo (aigu)</td>
      <td align="center">&acute;</td>
      <td align="center"><tt>['x]</tt> or <tt>[/x]</tt></td>
      <td align="center"><tt>[x']</tt> or <tt>[x/]</tt></td>

      </tr>
  <tr><td>acento circunflejo</td>
      <td align="center">&circ;</td>
      <td align="center"><tt>[^x]</tt></td>
      <td align="center"><tt>[x^]</tt></td>
      </tr>
  <tr><td>caron (s&iacute;mbolo en forma de v)</td>

      <td align="center"><font size="-2">&or;</font></td>
      <td align="center"><tt>[vx]</tt></td>
      <td align="center"><tt>[xv]</tt></td>
      </tr>
  <tr><td>breve (s&iacute;mbolo en forma de u)</td>
      <td align="center"><font size="-2">&cup;</font></td>
      <td align="center"><tt>[)x]</tt></td>

      <td align="center"><tt>[x)]</tt></td>
      </tr>
  <tr><td>tilde</td>
      <td align="center">&tilde;</td>
      <td align="center"><tt>[~x]</tt></td>
      <td align="center"><tt>[x~]</tt></td>
      </tr>

  <tr><td>cedilla</td>
      <td align="center">&cedil;</td>
      <td align="center"><tt>[,x]</tt></td>
      <td align="center"><tt>[x,]</tt></td>
      </tr>
  </tbody>
</table>

<h3><a name="f_chars">Caracteres no-Latinos</a></h3>
<p>
En algunos proyectos hay textos en caracteres no-latinos, es decir otros caracteres aparte de los �Latinos A-Z."  Por ejemplo: griego, cir�lico, hebreo o �rabe.
</p><p>
Para el griego debe hacer una transliteraci�n: es decir, convertir cada car�cter
del alfabeto griego en la letra equivalente de ASCII (letras latinas).  En la
interfaz de revisi�n hay una herramienta que hace esta tarea m�s f�cil.
</p>
<p>
Oprima el bot�n �Greek" (griego en ingl�s) (lo encontrar� al fondo de la interfaz de revisi�n) para
abrir la herramienta.  Haga clic en el car�cter griego que necesita y el car�cter
apropiado de ASCII aparecer� en la ventana de texto de la herramienta. Terminado
con todo el texto en griego, copie la transliteraci�n y pegue la en la p�gina que
est� revisando. Sit�e estos caracteres entre par�ntesis y el signo que indica
griego; as�... [Greek: texto transliterado].  Por ejemplo:
<b>&Beta;&iota;&beta;&lambda;&omicron;&sigmaf;</b> ser� revisado como
<tt>[Greek: Biblos]</tt>. (Que quiere decir Libro--�apropiado para DP!)
</p>
<p>
Si no est� seguro de su transliteraci�n, ponga una se�al (as� <tt>*</tt>) para llamar la
atenci�n a la persona que hace la siguiente ronda de revisi�n.
</p>
<p>
Para otros idiomas que no se puedan transliterar f�cilmente (como
cir�lico, hebreo o �rabe) deje el texto como aparece codificado por el
OCR y sit�e estos caracteres/palabras/frases entre par�ntesis y el signo
que indica el idioma si se sabe...as�: <tt>[Cyrillic:&nbsp;**]</tt>,
<tt>[Hebrew:&nbsp;**]</tt>,
<tt>[Arabic:&nbsp;**]</tt>.
Incluya los <tt>**</tt> para llamar la atenci�n del pos-procesador.
</p>
<!-- END RR -->

<ul compact>
  <li>Griego: <a href="http://www.gutenberg.org/howto/greek/"> Greek HOWTO</a> (de Proyecto Gutenberg) o vea la herramienta en la interfaz de revisi�n del proyecto.
  </li>
<li>Cir�lico: Aunque existe un sistema de transliteraci�n de cir�lico, le
sugerimos que intente una transliteraci�n solamente si domina el idioma.
Si no, m�rquelo tan solo de la manera indicada arriba. Puede que
encuentre �til <a href="http://learningrussian.com/transliteration.htm"> esta
tabla de transliteraci�n.</a>
  </li>
  <li>
Hebreo y �rabe: No lo intente a menos que domine el idioma. Hay muchas
dificultades para transliterar estos idiomas. Ni <a href="http://www.pgdpcanada.net">
Distributed Proofreaders</a> ni <a href="http://www.gutenberg.org/"> Project
Gutenberg</a> han elegido un m�todo estandardizado.</li>
</ul>

<h3><a name="fract_s">Fracciones</a></h3>
<p>Revise fracciones as�:<tt>2&frac12;</tt> se convierte en <tt>2-1/2</tt>.
El gui�n evita que se separen el n�mero y las fracciones cuando las l�neas se formateen en el pos-proceso.
</p>


<h3><a name="em_dashes">Guiones, el signo menos "-" y rayas</a></h3>
<p> Generalmente hay cuatro tipos de guiones que se encuentran en los libros:
  <ol compact>
    <li><i>Guiones de separaci�n</i>.
Se utilizan para juntar dos palabras, o a veces <b>juntan</b> prefijos y sufijos a las palabras.
    <br>
D�jelos como simple gui�n, sin ning�n espacio por delante o detr�s.<br>F�jese en la excepci�n del segundo caso.
    </li>
    <li><i>El signo menos "-"</i> (En-dash).
Estos pueden ser un poco m�s largos y se utilizan para indicar una <b>escala/rango</b> de n�meros o el signo <b>matem�tico</b>.
    <br>
Rev�salos como gui�n simple.  Los espacios (antes o despu�s) tienen que coincidir
con el texto original; normalmente no hay espacios para escalas de n�meros, y s�
antes del menos... a veces tambi�n detr�s de �l.
   </li>

    <li><i>Gui�n largo</i> (Em-dash, long dash).
 Estos separan las palabras&mdash;a veces para enfatizar algo, as�&mdash;o cuando el que habla vacila antes de completar la frase&mdash;!

Rev�selos como dos guiones (--) si son de tama�o normal, o cuatro
guiones (----) si el gui�n es m�s largo de lo normal. No deje ning�n
espacio en blanco antes ni tampoco despu�s, aunque parezca que en el
texto original haya espacios.
    </li>
    <li><i>Palabras o nombres omitidos a prop�sito</i>.
    <br>
Rev�selos como cuatro guiones (----). Si reemplazan una palabra, deje un espacio en blanco antes y despu�s de los guiones, como si se tratara de una palabra de verdad. Si es s�lo parte de una palabra la que est� sustituida, no deje espacio--j�ntelos con el resto de la palabra.<br>
Si la parte omitida de la palabra aparece de tama�o de un gui�n largo, rev�selo como tal: dos guiones.    </li>

  </ol>
<p>
Note: Si un gui�n largo (em-dash) aparece al principio o al final de la
l�nea del texto de OCR, j�ntelo con la otra l�nea de texto para que no
haya espacios alrededor de el. Solamente si el autor utiliz� un gui�n
largo (em-dash) al comienzo o al final del p�rrafo, l�nea de poes�a o
di�logo se debe dejar como est�. Vea los ejemplos que siguen.
</p>
<!-- END RR -->

<p><b>Ejemplos</b>&mdash;Guiones, signo menos "-" y rayas:
</p>

<table width="100%" align="center" border="1"  cellpadding="4" cellspacing="0" summary="Hyphens and Dashes">
  <tbody>
    <tr>
      <th valign="top" bgcolor="cornsilk">Imagen Original:</th>

      <th valign="top" bgcolor="cornsilk">Texto Revisado Correctamente:</th>
      <th valign="top" bgcolor="cornsilk">Tipo</th>
    </tr>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Gui&oacute;n</td>

    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Gui&oacute;n</td>
    </tr>
    <tr>

      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Gui&oacute;n</td>
    </tr>

    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Gui&oacute;n largo</td>

    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>

      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>

      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>

      <td valign="top"><tt>2-1/2</tt></td>
      <td>Signo menos</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague<br> on both your houses!&mdash;I am dead.</td>

      <td valign="top"><tt>I am hurt;--A plague<br> on both your houses!--I am dead.</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>

      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>

        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>

      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>Gui&oacute;n largo</td>
    </tr>
 <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to
	say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to
	say, but the left-hand cat interrupted her.</tt></td>

      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>Raya</td>

    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>Raya</td>
    </tr>

    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>

      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>Raya</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;I am not a d&mdash;d Yankee&rdquo;, he replied.</td>
      <td valign="top"><tt>"I am not a d--d Yankee", he replied.</tt></td>

      <td>Gui&oacute;n largo</td></tr>
  </tbody>
</table>

<h3><a name="eol_hyphen">Gui�n al final de la l�nea</a></h3>
<p>
Cuando hay un gui�n que corta la palabra al final de la l�nea, junte las
dos partes de la palabra. Si la palabra normalmente lleva gui�n (como
pos-proceso o well-meaning), junte sus dos partes dejando el gui�n donde
est�. Si el gui�n se puso solamente para partir una palabra que no cabe
entera en la l�nea (y no se trata de una palabra que normalmente lleva
gui�n), junte las dos partes de la palabra y quite el gui�n. Coloque la
palabra rejuntada al final de la l�nea superior, y corte la l�nea
despu�s de ella para mantener el formato de l�nea. Esto facilitar� el
trabajo de voluntarios que sigan la revisi�n en siguientes rondas. Vea
<a href="#em_dashes">Guiones, el signo menos "-" y rayas</a> para ver los ejemplos en cada caso (nar-row
se convierte en narrow, pero low-lying no pierde su gui�n). Si a la
palabra le sigue la puntuaci�n, suba esta tambi�n al final de la l�nea
superior, junto a la palabra rejuntada.
</p><p>Palabras como �to-day" y
"to-morrow" que hoy d�a no llevan gui�n aparecen con �l en muchos libros
antiquos que estamos revisando. Deje estas palabras con gui�n como el
autor las escribi�. Si no est� seguro si el autor escribi� la palabra
con gui�n o sin �l, dejelo como est�, ponga un asterisco * a
continuac��n, y rejunte la palabra. As�: to-*day. El asterisco llamar�
atenci�n del pos-procesador, el que tiene acceso a todas las p�ginas, y
puede decidir sobre la pauta que sigui� el autor en la obra.
</p>

<h3><a name="eop_hyphen">Gui�n al final de la p�gina</a></h3>
<p>
Cuando hay un gui�n que corta la palabra al final de la p�gina, deje el
gui�n y ponga un asterisco <tt>*</tt> a continuaci�n.<br>
Por ejemplo:<br>
   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;something Pat had already become accus-<br>
   se convierte en:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>something Pat had already become accus-*</tt>
</p>
<p>
En la p�gina que empieza con la segunda parte de la palabra de la p�gina
anterior, o con un gui�n, ponga un asterisco <tt>*</tt> al principio de todo.

La continuaci�n del ejemplo de arriba:<br>

   &nbsp;<br>
   &nbsp; &nbsp; &nbsp; &nbsp;tomed to from having to do his own family<br>
   se convierte en:<br>
   &nbsp; &nbsp; &nbsp; &nbsp;<tt>*tomed to from having to do his own family</tt>

</p>
<p>Estos indiquen al pos-procesor que las dos partes deben juntarse.</p>


<h3><a name="para_space">P�rrafos</a></h3>
<p>
Ponga una l�nea en blanco entre los p�rrafos. No sangre los p�rrafos; si el p�rrafo est� ya sangrado no pierda tiempo en quitar los espacios&mdash;eso se hace autom�ticamente en el pos-proceso.
</p>
<p>Vea <a href="#para_side">Notas al margen</a> para un ejemplo.
</p>

<h3><a name="mult_col">M�ltiples Columnas</a></h3>
<p>Revise el texto que est� editado en dos columnas como una sola.
</p>
<p>
El texto de m�ltiples columnas tambi�n se revisa como si fuera de una
sola columna. Empiece con la primera de la izquierda, siga con la que
est� a su derecha, y as� hasta la �ltima columna de la derecha. No es
necesario indicar d�nde acaba una y empieza la siguiente columna.
Simplemente j�ntelo todo en una sola columna.
</p>
<p>Vea tambi�n <a href="#bk_index">�ndices</a> y <a href="#tables">Tablas</a> secciones de las reglas de
revisi�n.
</p>


<h3><a name="blank_pg">P�gina en blanco</a></h3>

<p>
La mayor�a de las p�ginas en blanco, o p�ginas con una ilustraci�n y sin
texto, ya est�n marcadas con <tt>[Blank Page]</tt>. D�jelas como est�n. Si la
p�gina est� en blanco y no est� marcada con [Blank Page] no es necesario
a�adir esta marca.
</p>
<p>
Si el texto est� donde normalmente tiene que estar pero la imagen de la
p�gina del libro original est� en blanco, o; si la imagen est� pero no
aparece el texto, siga las instrucciones para <a href="#bad_image">Im�genes de baja
calidad</a> o <a href="#bad_text">Imagen
que no corresponde al texto</a>, correlativamente.
</p>

<h3><a name="page_hf">Encabezado y pie de p�gina</a></h3>
<p>
Elimine del texto todos los encabezados y pies de p�gina, pero <em>no</em>
elimine las <a href="#footnotes">Notas al pie de p�gina</a>.
</p>
<p>
Los encabezados normalmente aparecen al principio de la p�gina, con el
n�mero de la p�gina al lado. Pueden ser iguales para todas las p�ginas
del libro, (t�tulo del libro y el nombre del autor); pueden ser iguales
para todas las p�ginas de un cap�tulo (normalmente el t�tulo o n�mero
del cap�tulo): o pueden ser diferentes en cada p�gina (una descripci�n
de lo que trata el cap�tulo). Elim�nelos a todos, sin importar la clase
que sean, incluyendo el n�mero de p�gina.
</p>
<!-- END RR -->

<p>
Los <a href="#chap_head">T�tulos de cap�tulo</a> empiezan un poco m�s
abajo en la p�gina y el n�mero de la p�gina no est� en la misma l�nea.
Vea la secci�n siguiente para ejemplos.
</p>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Page Headers and Footers">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen del Ejemplo:</th></tr>

    <tr align="left">
      <td width="100%" valign="top">
      <img src="foot.png" alt="" width="500" height="850"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto Revisado Correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
    <tt>In the United States?[*] In a railroad? In a mining company?<br>
    In a bank? In a church? In a college?<br>
    <br>
    Write a list of all the corporations that you know or have<br>
    ever heard of, grouping them under the heads public and private.<br>
    <br>

    How could a pastor collect his salary if the church should<br>
    refuse to pay it?<br>
    <br>
    Could a bank buy a piece of ground �on speculation?" To<br>
    build its banking-house on? Could a county lend money if it<br>
    had a surplus? State the general powers of a corporation.<br>

    Some of the special powers of a bank. Of a city.<br>
    <br>
    A portion of a man's farm is taken for a highway, and he is<br>
    paid damages; to whom does said land belong? The road intersects<br>
    the farm, and crossing the road is a brook containing<br>
    trout, which have been put there and cared for by the farmer;<br>

    may a boy sit on the public bridge and catch trout from that<br>
    brook? If the road should be abandoned or lifted, to whom<br>
    would the use of the land go?<br>
    <br>
    <br>
    CHAPTER XXXV.<br>
    <br>

    Commercial Paper.<br>
    <br>
    Kinds and Uses.--If a man wishes to buy some commodity<br>
    from another but has not the money to pay for<br>
    it, he may secure what he wants by giving his written<br>
    promise to pay at some future time. This written<br>

    promise, or note, the seller prefers to an oral promise<br>
    for several reasons, only two of which need be mentioned<br>
    here: first, because it is prima facie evidence of<br>
    the debt; and, second, because it may be more easily<br>
    transferred or handed over to some one else.<br>
    <br>

    If J. M. Johnson, of Saint Paul, owes C. M. Jones,<br>
    of Chicago, a hundred dollars, and Nelson Blake, of<br>
    Chicago, owes J. M. Johnson a hundred dollars, it is<br>
    plain that the risk, expense, time and trouble of sending<br>
    the money to and from Chicago may be avoided,<br>
    <br>

    * The United States: "Its charter, the constitution. * * * Its flag the<br>
    symbol of its power; its seal, of its authority."--Dole.
    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="chap_head">T�tulos de cap�tulo</a></h3>

<p>Revise los t�tulos de cap�tulo como aparecen en el texto.
</p>
<p>
Un t�tulo de cap�tulo puede empezar un poco m�s abajo que el <a href="#page_hf">encabezado
de la p�gina</a>, y el n�mero de p�gina no est� en la misma l�nea. T�tulos
de cap�tulo a veces se imprimen en may�sculas. Si es as�, deje todas las
may�sculas tal y como est�n.
</p>
<p>
Preste atenci�n al primer p�rrafo del cap�tulo. Puede que el impresor no
haya incluido (o el programa de OCR no haya reconocido) las comillas con
las que empieza el primer p�rrafo. Si el autor empieza este p�rrafo con
di�logo, introduzca las comillas omitidas.
</p>
<!-- END RR -->



<h3><a name="illust">Ilustraciones - Im�genes</a></h3>
<p>
Revise cualquier texto que acompa�e la ilustraci�n, preservando los
quiebros de l�nea. Si la nota que acompa�a la ilustraci�n o imagen se
encuentra en medio de un p�rrafo, sep�rela del resto del texto del
p�rrafo con una l�nea en blanco antes, y otra despu�s de esta nota. Si
<b>no existe</b> tal nota, el formato de la ilustraci�n se deja para los
formateadores.
</p>
<p>
La mayor�a de las p�ginas que contienen solamente la ilustraci�n ya
est�n marcadas con <tt>[Blank Page]</tt>. Deje esta marca tal y como est�.
</p>

<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Illustration">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Una ilustraci�n / imagen de ejemplo:
      </th>

    </tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="illust.png" alt=""
          width="500" height="525"> <br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>

   </tr>
    <tr>
    <td width="100%" valign="top">
      <table summary="" border="0" align="left"><tr>
      <td>
      <p><tt>Martha told him that he had always been her ideal and<br>
      that she worshipped him.<br>
      <br>

      Frontispiece<br>
      Her Weight in Gold
      </tt></p>
      </td></tr></table>
    </td>
    </tr>
  </tbody>
</table>

<br>

<table width="100%" align="center" border="1"  cellpadding="4"
 cellspacing="0" summary="Illustration in Middle of Paragraph">
  <tbody>
   <tr>
     <th align="left" bgcolor="cornsilk">Una ilustraci�n / imagen de ejemplo: (Una ilustraci�n en el medio del p�rrafo)</th>
   </tr>
   <tr align="left">
     <td width="100%" valign="top"> <img src="illust2.png" alt=""
         width="500" height="514"> <br>
     </td>

   </tr>
   <tr>
     <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
   </tr>
    <tr valign="top">
     <td>
<table summary="" border="0" align="left"><tr><td>
     <p><tt>
     such study are due to Italians. Several of these instruments<br>

     have already been described in this journal, and on the present<br>
     </tt></p>
     <p><tt>FIG. 1.--APPARATUS FOR THE STUDY OF HORIZONTAL<br>
     SEISMIC MOVEMENTS.</tt></p>
     <p><tt>
      occasion we shall make known a few others that will<br>
     serve to give an idea of the methods employed.<br>

     </tt></p>
     <p><tt>
     For the observation of the vertical and horizontal motions<br>
     of the ground, different apparatus are required. The</tt>
     </p>
</td></tr></table>
    </td>
    </tr>

  </tbody>
</table>

<h3><a name="footnotes">Notas al pie de p�gina</a></h3>
<p><b>Las notas al pie se ubican fuera de la l�nea de texto</b>;
Es decir, la nota se deja al fondo de la p�gina, y en el texto se coloca
un marcador al lado de la palabra a la que se refiere la nota al pie de
p�gina.
</p>
<p>
La letra, el n�mero o cualquier otro car�cter que indica una nota al
pie, debe ponerse entre par�ntesis cuadrados ([ y ]) justo al lado de la
palabra<tt>[1]</tt> a la que se refiere la nota o, al lado de su puntuaci�n,<tt>[2]</tt>
como se ve en esta frase.
</p>
<p>
Cuando las notas est�n indicadas con caracteres especiales (*,
&dagger;, &Dagger;, &sect;, etc.), reempl�celos todos con [*] en el texto, y
ponga un asterisco * al
principio de la nota al pie.
</p>
<p>
Revise el texto de la nota al pie compar�ndolo con el texto original
preservando los cambios / quiebros de l�nea. Deje el texto de la nota al
fondo de la p�gina. Est� seguro de utilizar la misma marca para la nota
al pie, que la que utiliza junto a la palabra a la que esta se refiere
(sea *, 1, 2 etc.).
</p>
<p>
Ponga cada nota al pie en una nueva l�nea. Ponga una l�nea en blanco al
final de cada nota al pie de p�gina (si hay m�s que una).
</p>

<!-- END RR -->

<p>Vea un ejemplo aqu�: <a href="#page_hf">Encabezado y pie de p�gina</a>
para un ejemplo.

</p>
<p>
Si hay una referencia a una nota al pie en el texto pero ninguna nota
aparece al fondo de la p�gina, deje la referencia donde est� y no se
preocupe: es una situaci�n com�n en los libros t�cnicos/cient�ficos
donde se juntan todas las notas al final del cap�tulo. Vea �Notas al
final� abajo.
</p>

<table width="100%" border="1"  cellpadding="4" cellspacing="0" align="center" summary="Footnote Examples">
  <tbody>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto original:</th>
    </tr>
    <tr>
      <td valign="top">

<table summary="" border="0" align="left"><tr><td>
    The principal persons involved in this argument were Caesar<sup>1</sup>, former military<br>
    leader and Imperator, and the orator Cicero<sup>2</sup>. Both were of the aristocratic<br>
    (Patrician) class, and were quite wealthy.<br>
    <hr align="left" width="50%" noshade size="2">
    <font size=-1><sup>1</sup> Gaius Julius Caesar.</font><br>

    <font size=-1><sup>2</sup> Marcus Tullius Cicero.</font>
</td></tr></table>
      </td>
    </tr>
    <tr>
      <th valign="top" align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>

      <tr valign="top">
      <td>
<table summary="" border="0" align="left"><tr><td>
    <tt>The principal persons involved in this argument were Caesar[1], former military</tt><br>
    <tt>leader and Imperator, and the orator Cicero[2]. Both were of the aristocratic</tt><br>
    <tt>(Patrician) class, and were quite wealthy.</tt><br>
    <br>
    <tt>1 Gaius Julius Caesar.</tt><br>

    <br>
    <tt>2 Marcus Tullius Cicero.</tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<p>
En algunos libros las notas al pie est�n separadas del texto principal
por una l�nea horizontal. Omita esa l�nea, y deje tan solo una l�nea en
blanco entre el texto principal y las notas al pie. (Vea ejemplo de
arriba)
</p>
<p><b>Notas al final</b>
son notas al pie que de vez en cada p�gina se colocan todas juntas al
final del cap�tulo, o al final del libro. Se revisan de la misma manera
que las notas al pie. Si encuentra una referencia a alguna nota en el
texto, retenga el n�mero o la letra. Si revisa las p�ginas finales del
libro donde se encuentran todas las notas, ponga una l�nea en blanco al
final de cada una de ellas, para que est� claro donde empieza y termina
cada nota.
</p>
<!-- Need an example of Endnotes, maybe? Good idea!-->

<p><b>Notas al pie en <a href="#poetry">Poes�a</a></b>
se tratan de la misma manera que otras notas al pie.<br> <br>

<b>Notas al pie en <a href="#tables">Tablas</a></b>
se deben quedar en el lugar en el que est�n en el texto original.
</p>

<table width="100%" align="center" border="1" cellpadding="4" cellspacing="0" summary="Footnotes">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Poes�a original con una nota al pie:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    Mary had a little lamb<sup>1</sup><br>
    &nbsp;&nbsp;&nbsp;Whose fleece was white as snow<br>

    And everywhere that Mary went<br>
    &nbsp;&nbsp;&nbsp;The lamb was sure to go!<br>
    <hr align="left" width="50%" noshade size=2>
    <font size=-2><sup>1</sup> This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.</font>
</td></tr></table>

      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Poes�a revisada correctamente:</th></tr>
    <tr>
      <td valign="top">
<table summary="" border="0" align="left"><tr><td>
    <tt>
    Mary had a little lamb[1]<br>

    Whose fleece was white as snow<br>
    And everywhere that Mary went<br>
    The lamb was sure to go!<br>
    <br>
    1 This lamb was obviously of the Hampshire breed,<br>
    well known for the pure whiteness of their wool.<br>

    </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="poetry">Poes�a</a></h3>
<p>
Introduzca una l�nea en blanco al comienzo de la poes�a o epigrama y
otra al final, para que los formateadores puedan ver claramente donde
empieza y donde se acaba el formato especial.
</p>
<p>
Justifique todas las l�neas a la izquierda y mantenga los cambios /
quiebras de l�nea. No intente sangrar la poes�a. Los formateadores lo
har�n. Introduzca una l�nea en blanco entre las estrofas.
</p>

<p><b>Notas al pie</b>
en poes�a deben tratarse de la misma manera que cualquier nota al pie en
el texto com�n. Vea <a href="#footnotes">Notas al pie de p�gina</a>
para m�s detalles.
</p>
<p><b>La numeraci�n de las l�neas</b>
en poes�a debe conservarse. Separe los n�meros del texto con algunos
espacios. Vea las instrucciones para los <a href="#line_no">N�meros de l�nea</a>.
</p>
<p>
Compruebe en los <a href="#comments">Comentarios del proyecto</a> que est� revisando, si existen indicaciones especiales.
</p>
<!-- END RR -->

<br>
<!-- Need an example that shows overly long lines of poetry, rather than relative indentation -->

<table width="100%" align="center" border="1"  cellpadding="4"
      cellspacing="0" summary="Poetry Example">
 <tbody>
   <tr><th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th></tr>
   <tr align="left">
     <th width="100%" valign="top"> <img src="poetry.png" alt=""
         width="500" height="508"> <br>

     </th>
   </tr>
   <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
   <tr>
     <td width="100%" valign="top">


<table summary="" border="0" align="left"><tr><td>
<tt>
to the scenery of his own country:<br></tt>

<p><tt>
Oh, to be in England<br>
Now that April's there,<br>
And whoever wakes in England<br>
Sees, some morning, unaware,<br>
That the lowest boughs and the brushwood sheaf<br>
Round the elm-tree hole are in tiny leaf,<br>
While the chaffinch sings on the orchard bough<br>
In England--now!</tt>

</p><p><tt>
And after April, when May follows,<br>
And the whitethroat builds, and all the swallows!<br>
Hark! where my blossomed pear-tree in the hedge<br>
Leans to the field and scatters on the clover<br>
Blossoms and dewdrops--at the bent spray's edge--<br>
That's the wise thrush; he sings each song twice over,<br>
Lest you should think he never could recapture<br>
The first fine careless rapture!<br>

And though the fields look rough with hoary dew,<br>
All will be gay, when noontide wakes anew<br>
The buttercups, the little children's dower;<br>
--Far brighter than this gaudy melon-flower!<br>
</tt>
</p><p><tt>
So it runs; but it is only a momentary memory;<br>
and he knew, when he had done it, and to his</tt>
</p>
</td></tr></table>

     </td>
   </tr>
 </tbody>
</table>

<h3><a name="para_side">Notas al margen</a></h3>
<p>
En algunos libros existen breves descripciones del p�rrafo al margen del
texto. Revise el texto de la �nota al margen� compar�ndolo con el texto
original, preservando los cambios de l�nea. Deje una l�nea en blanco
antes y despu�s de la �nota al margen� para que esta se pueda distinguir
del resto del texto. Es posible que el programa de OCR haya colocado el
texto de la nota en cualquier parte de la p�gina, hasta entremezclado
con el resto del texto de la p�gina. Separe el texto de la nota del
resto del texto, coloc�ndolo todo junto. No se preocupe de lugar en la
p�gina conde coloca el texto de la nota. Los formateadores lo pondr�n en
el lugar apropiado.</p>

<!-- END RR -->

  <table width="100%" align="center" border="1" cellpadding="4"
       cellspacing="0" summary="Sidenotes"> <col width="128*">

  <tbody>
    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr valign="top">
      <td width="100%" align="left"><img src="side.png" alt=""
          width="550" height="800"><br>
      </td>
    </tr>

    <tr valign="top">
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr valign="top">
      <td width="100%">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    Burning<br>

    discs<br>
    thrown into<br>
    the air.<br>
    <br>
    that such as looked at the fire holding a bit of larkspur<br>
    before their face would be troubled by no malady of the<br>

    eyes throughout the year.[1] Further, it was customary at<br>
    W�rzburg, in the sixteenth century, for the bishop's followers<br>
    to throw burning discs of wood into the air from a mountain<br>
    which overhangs the town. The discs were discharged by<br>
    means of flexible rods, and in their flight through the darkness<br>
    presented the appearance of fiery dragons.[2]<br>

    <br>
    The Midsummer<br>
    fires in<br>
    Swabia.<br>
    <br>

    In the valley of the Lech, which divides Upper Bavaria<br>

    from Swabia, the midsummer customs and beliefs are, or<br>
    used to be, very similar. Bonfires are kindled on the<br>
    mountains on Midsummer Day; and besides the bonfire<br>
    a tall beam, thickly wrapt in straw and surmounted by a<br>
    cross-piece, is burned in many places. Round this cross as<br>
    it burns the lads dance with loud shouts; and when the<br>

    flames have subsided, the young people leap over the fire in<br>
    pairs, a young man and a young woman together. If they<br>
    escape unsmirched, the man will not suffer from fever, and<br>
    the girl will not become a mother within the year. Further,<br>
    it is believed that the flax will grow that year as high as<br>
    they leap over the fire; and that if a charred billet be taken<br>

    from the fire and stuck in a flax-field it will promote the<br>
    growth of the flax.[3] Similarly in Swabia, lads and lasses,<br>
    hand in hand, leap over the midsummer bonfire, praying<br>
    that the hemp may grow three ells high, and they set fire<br>
    to wheels of straw and send them rolling down the hill.<br>
    Among the places where burning wheels were thus bowled<br>

    down hill at Midsummer were the Hohenstaufen mountains<br>
    in Wurtemberg and the Frauenberg near Gerhausen.[4]<br>
    At Deffingen, in Swabia, as the people sprang over the mid-*<br>
    <br>
    Omens<br>
    drawn from<br>

    the leaps<br>
    over the<br>
    fires.<br>
    <br>
    Burning<br>
    wheels<br>

    rolled<br>
    down hill.<br>
    <br>
    1 Op. cit. iv. 1. p. 242. We have<br>
    seen (p. 163) that in the sixteenth<br>
    century these customs and beliefs were<br>

    common in Germany. It is also a<br>
    German superstition that a house which<br>
    contains a brand from the midsummer<br>
    bonfire will not be struck by lightning<br>
    (J. W. Wolf, Beitr�ge zur deutschen<br>
    Mythologie, i. p. 217, � 185).<br>

    <br>
    2 J. Boemus, Mores, leges et ritus<br>
    omnium gentium (Lyons, 1541), p.<br>
    226.<br>
    <br>
    3 Karl Freiherr von Leoprechting,<br>
    Aus dem Lechrain (Munich, 1855),<br>

    pp. 181 sqq.; W. Mannhardt, Der<br>
    Baumkultus, p. 510.<br>
    <br>
    4 A. Birlinger, Volksth�mliches aus<br>
    Schwaben (Freiburg im Breisgau, 1861-1862),<br>
    ii. pp. 96 sqq., � 128, pp. 103<br>

    sq., � 129; id., Aus Schwaben (Wiesbaden,<br>
    1874), ii. 116-120; E. Meier,<br>
    Deutsche Sagen, Sitten und Gebr�uche<br>
    aus Schwaben (Stuttgart, 1852), pp.<br>
    423 sqq.; W. Mannhardt, Der Baumkultus,<br>
    p. 510.<br>

    </tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>

<h3><a name="tables">Tablas</a></h3>
<p>La tarea del revisor es estar seguro que toda la informaci�n en la tabla
est� correctamente revisada. Los detalles de formato de tablas ser�n
tratados m�s adelante. Deje suficiente espacio entre los datos de
diversas columnas para que est� claro donde empieza y donde acaba cada
dato. Retenga los cambios / quiebros de l�nea.
</p>

<p><b>Notas al pie</b>
en las tablas deben quedarse donde est�n en el original. Vea <a href="#footnotes">Notas al
pie de p�gina</a> para m�s detalles.
</p>
<!-- END RR -->
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 1">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th></tr>
    <tr align="left">
      <td width="100%" valign="top"> <img src="table1.png" alt="" width="500" height="142"><br>

      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
Deg. C.  Millimeters of Mercury. Gasolene.
Pure Benzene.

-10&deg;  13.4  43.5
 0&deg;  26.6  81.0
+10&deg;  46.6  132.0
20&deg;  76.3  203.0
40&deg;  182.0  301.8

</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Table Example 2">
  <tbody>
    <tr><th align="left" bgcolor="cornsilk">Imagen de ejemplo:</th></tr>
    <tr align="left">

      <td width="100%" valign="top"> <img src="table2.png" alt="" width="500" height="304"><br>
      </td>
    </tr>
    <tr><th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th></tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<pre>
TABLE II.

Flat strips compared   Copper   Copper
with round wire 30 cm.  Iron. Parallel wires 30 cm. in  Iron.
in length.             length.

Wire 1 mm. diameter   20  100  Wire 1 mm. diameter   20 100

        STRIPS.      SINGLE WIRE.
0.25 mm. thick, 2 mm.
  wide  15  35  0.25 mm. diameter   16   48
Same, 5 mm. wide       13  20  Two similar wires    12  30
 "   10  "    "   11   15  Four    "    "     9   18
 "   20  "    "    10  14  Eight  "    "   8   10
 "   40  "    "    9   13  Sixteen "    "     7    6
Same strip rolled up in  Same 16 wires bound
  the form of a wire  17   15    close together   18    12

</pre>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<h3><a name="title_pg">Portada&mdash;Contraportada</a></h3>
<p>
Revise todo el texto como se ve en la imagen de la p�gina, sean las
letras may�sculas o min�sculas, etc., los a�os de la publicaci�n, o
derechos del autor.
</p>
<p>
En los libros antiguos frecuentemente la primera letra es grande y
ornamentada&mdash;rev�sela como una letra normal.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Title Page Example">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagen del ejemplo:
      </th>
    </tr>
    <tr align="left">

      <td width="100%" valign="top"><img src="title.png" width="500"
          height="520" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
      <p><tt>GREEN FANCY</tt>
      </p>
      <p><tt>BY</tt></p>
      <p><tt>GEORGE BARR McCUTCHEON</tt></p>
      <p><tt>AUTHOR OF �GRAUSTARK," �THE HOLLOW OF HER HAND,"<br>
         �THE PRINCE OF GRAUSTARK," ETC.</tt></p>

      <p><tt>WITH FRONTISPIECE BY<br>
         C. ALLAN GILBERT</tt></p>
      <p><tt>NEW YORK<br>
         DODD, MEAD AND COMPANY.</tt></p>
      <p><tt>1917</tt></p>
</td></tr></table>
      </td>

    </tr>
  </tbody>
</table>

<h3><a name="toc">La tabla de contenidos</a></h3>
<p>
Revise la tabla de contenidos exactamente como est� en el libro, ya sean
las letras may�sculas o min�sculas, etc. Conserve los n�meros de las
p�ginas.
</p>
<p>
Haga caso omiso de los puntos o asteriscos utilizados para alinear los
n�meros de las p�ginas. Estos ser�n eliminados m�s adelante en el
proceso.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="TOC">
  <tbody>

    <tr>
      <th align="left" bgcolor="cornsilk">
      Imagen del ejemplo:
      </th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top">
      <p><img src="tablec.png" alt="" width="500" height="650"></p>
      </td>

    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
      <p><tt>CONTENTS</tt></p>

      <p><tt><br>
          CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<br>
          <br>

          I. THE FIRST WAYFARER AND THE SECOND WAYFARER<br>
          MEET AND PART ON THE HIGHWAY&nbsp;&nbsp;.....&nbsp;1<br>
          <br>
          II.  THE FIRST WAYFARER LAYS HIS PACK ASIDE AND<br>
          FALLS IN WITH FRIENDS&nbsp;&nbsp;....&nbsp;...&nbsp;15<br>

          <br>
          III. MR. RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<br>
          AND TWO MEN RIDE AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<br>
          <br>
          IV. AN EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<br>
          TRAGEDY, AND A MAN WHO SAID �THANK YOU"&nbsp;&nbsp;&nbsp;50<br>

          <br>
          V. THE FARM-BOY TELLS A GHASTLY STORY, AND AN<br>
          IRISHMAN ENTERS&nbsp;&nbsp;..&nbsp;&nbsp;..&nbsp;67<br>
          <br>
          VI. CHARITY BEGINS FAR FROM HOME, AND A STROLL IN<br>
          THE WILDWOOD FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<br>

          <br>
          VII. SPUN-GOLD HAIR, BLUE EYES, AND VARIOUS ENCOUNTERS&nbsp;&nbsp;...&nbsp;&nbsp;&nbsp;103<br>
          <br>
          VIII. A NOTE, SOME FANCIES, AND AN EXPEDITION IN<br>
          QUEST OF FACTS&nbsp;&nbsp;..&nbsp;,,&nbsp;120<br>

          <br>
          IX. THE FIRST WAYFARER, THE SECOND WAYFARER, AND<br>
          THE SPIRIT OF CHIVALRY ASCENDANT&nbsp;&nbsp;&nbsp;,&nbsp;&nbsp;134<br>
          <br>
          X. THE PRISONER OF GREEN FANCY, AND THE LAMENT OF<br>
          PETER THE CHAUFFEUR&nbsp;...&nbsp;&nbsp;&nbsp;....148<br>

          <br>
          XI. MR. SPROUSE ABANDONS LITERATURE AT AN EARLY<br>
          HOUR IN THE MORNING&nbsp;..&nbsp;&nbsp;...&nbsp;&nbsp;,&nbsp;167<br>
          <br>
          XII. THE FIRST WAYFARER ACCEPTS AN INVITATION, AND<br>

          MR. DILLINGFORD BELABORS A PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<br>
          <br>
          XIII. THE SECOND WAYFARER RECEIVES TWO VISITORS AT<br>
          MIDNIGHT &nbsp;,,,..&nbsp;&nbsp;....&nbsp;199<br>
          <br>

          XIV. A FLIGHT, A STONE-CUTTER'S SHED, AND A VOICE<br>
          OUTSIDE&nbsp;&nbsp;&nbsp;,,,..&nbsp;&nbsp;....,&nbsp;221<br>
      </tt>
</td></tr></table>
      </td>
    </tr>
  </tbody>

</table>



<h3><a name="bk_index">�ndices</a></h3>
<p>
Retenga los n�meros de las p�ginas en las p�ginas del �ndice. No es
necesario alinear los n�meros como est�n en la imagen. S�lo est� seguro
que los n�meros y la puntuaci�n sean correctos.</p>
<p>Al �ndice se le dar� formato m�s adelante en el proceso. La tarea del revisor est� en
asegurarse que todo el texto y todos los n�meros sean correctos.
</p>
<!-- END RR -->


<h3><a name="play_n">Obras de teatro&mdash;Nombres de actores&mdash;Directrices esc�nicas</a></h3>

<p>Para todas las obras de teatro:</p>
<ul compact>
 <li>Trate cada cambio de personaje como un p�rrafo nuevo, con una l�nea en blanco entre los di�logos.
<li>
Revise las directrices esc�nicas como est�n en el texto.<br>
Si se encuentran en una l�nea nueva, p�ngalas en una l�nea nueva; si est�n al final de un di�logo, d�jelas as�.
<br>A menudo las directrices esc�nicas comienzan con un par�ntesis que no se cierra. D�jelo as�; no cierre el par�ntesis.</li>
 <li>
A veces, especialmente si la obra de teatro tiene un metro fijo, una
palabra puede estar cortada debido al tama�o de la p�gina y la segunda
parte de la palabra est� justo encima o debajo del principio de la
misma despu�s de un par�ntesis: (. Por favor, una la palabra y col�quela en la l�nea que le
corresponde.
<br>
   Vea el <a href="#play4">ejemplo abajo</a>.</li>
</ul>
<p>
Por favor, lea los <a href="#comments">Comentarios del proyecto</a>
para ver si el gerente del proyecto ha dado instrucciones
especificas al proyecto.
</p>
<!-- END RR -->

<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 1">
  <tbody>
    <tr>

      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="play1.png" width="500"
          height="430" alt="title page image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>

    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
Clow. Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
Lap. These marks are ugly.

</tt></p><p><tt>
Clow. He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
Lap. But why a Peel-crow here?
</tt></p><p><tt>
Clow. I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
Lap. How slave? look you, Sir,<br>
Did not I say, this Whirrit, and this Bob,<br>
Should be both Pica Roman.
</tt></p><p><tt>

Clow. So said I, Sir, both Picked Romans,<br>
And he has made 'em Welch Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
Lap. Hay-day; a Souse, Italica?
</tt></p><p><tt>
Clow. Yes, that may hold, Sir,<br>
Souse is a bona roba, so is Flops too.</tt></p>
</td></tr></table>
      </td>
    </tr>

  </tbody>
</table>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Play Example 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Imagen del ejemplo:</th>
    </tr>
    <tr align="left">

      <td width="100%" valign="top"><img src="play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texto revisado correctamente:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">

<table summary="" border="0" align="left"><tr><td>
<p><tt>
Am. Sure you are fasting;<br>
Or not slept well to night; some dream (Ismena?)<br>
<br>
Ism. My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
</tt></p>
</td></tr></table>
      </td>

    </tr>
  </tbody>
</table>


<h3><a name="anything">Cualquier otra cosa que necesite tratamiento especial o no sepa como tratar</a></h3>
<p>
Si encuentra, mientras est� revisando, algo que no est� explicado en
esta gu�a de revisi�n o cree que necesite un tratamiento especial o no
est� seguro c�mo tratarlo, entre al <a href="#comments">Foro/Discusi�n sobre este
proyecto</a>=Project Discussion, (un enlace en los
<a href="#comments">Comentarios del proyecto</a>=Project
Comments), y mande un mensaje, anotando el n�mero de la p�gina en la cual
est� (n� de png). Ponga tambi�n una nota en el texto que est� revisando,
explicando el problema. Su nota avisar� al pr�ximo revisor, al
formateador y el pos-procesor cual es la duda o el problema.
</p>
<p>

Empiece su nota con un par�ntesis cuadrado y dos asteriscos
<tt>[**</tt> as�, y
term�nela con otro par�ntesis cuadrado <tt>]</tt>. Esto la separar� claramente del
resto del texto del autor y llamar� la atenci�n del pos-procesor para
tratar esta parte del texto de la manera apropiada. Si el revisor
anterior ha dejado una nota, Ud. puede a�adir otra diciendo que est� de
acuerdo o desacuerdo. Aunque sepa la respuesta, de ninguna manera debe
eliminar ning�n comentario dejado por otros revisores. Si ha encontrado
un enlace o una fuente que clarifica el problema, por favor c�telo para
que el pos-procesor pueda referirse a ello.
</p>
<p>
Si est� revisando en las rondas m�s avanzadas, y encuentra una nota de
un revisor anterior y sabe la respuesta a su pregunta, por favor t�mese
un momento para enviarle un mensaje privado haciendo clic en su nombre
en la interfaz, explic�ndole c�mo tratar la situaci�n en el futuro. Por
favor, como ya est� advertido, no elimine su nota.
</p>

<h3><a name="prev_notes">Notas y comentarios de revisores anteriores</a></h3>
<p>
Es <b>imprescindible</b> que cualquier nota o comentario dejado por un revisor
anterior se deje tal i como est�. Puede a�adir un acuerdo o desacuerdo a
la nota existente. Pero aun que sepa la respuesta, de ninguna manera
elimine la nota o comentario. Si ha encontrado una fuente que clarifica
el problema, por favor, c�telo para que el pos-procesor pueda
consultarlo.
</p>
<p>
Si est� dando formato al texto en una ronda avanzada y encuentra una
nota de un voluntario de una ronda anterior y sabe la respuesta, por
favor, t�mese un momento para enviarle un mensaje privado explicando
c�mo tratar la situaci�n en el futuro. Por favor, como ya est� dicho, no
elimine la nota original.
</p>
<!-- END RR -->

<table width="100%" border="0" cellspacing="0" summary="Common Problems">
  <tbody>

    <tr>
      <td bgcolor="silver">&nbsp;</td>
    </tr>
  </tbody>
</table>

<h2>Problemas comunes</h2>

<h3><a name="OCR_1lI">Problemas de OCR: 1-l-I</a></h3>
<p>
Frecuentemente el OCR no puede distinguir entre el n�mero 1 (uno), la
letra min�scula l (ele) y la letra may�scula I (i). Es especialmente el
caso con los libros antiguos cuyas p�ginas est�n en malas condiciones.
</p>

<p>
Est� atento a estos errores. Lea el contexto de la frase para averiguar
cual es la letra apropiada. �Tenga cuidado!, porque su mente corregir�
autom�ticamente estos errores mientras lee.
</p>
<p>Encontrar estos errores es m�s f�cil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>

<h3><a name="OCR_0O">Problemas de OCR: 0-O</a></h3>
<p>
Frecuentemente el OCR no puede distinguir entre el n�mero 0 (cero) y la
letra may�scula O (o). Es especialmente el caso con los libros antiguos
cuyas p�ginas est�n en malas condiciones.
</p>
<p>
Est� atento a estos errores. Lea el contexto de la frase para averiguar
cual es la letra apropiada. �Tenga cuidado!, porque su mente corregir�
autom�ticamente estos errores mientras lee.
</p>
<p>
Encontrar estos errores es m�s f�cil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>

<h3><a name="OCR_scanno">Problemas de OCR: Scannos</a></h3>
<p>
Otro problema del OCR es el reconocimiento equivocado de los caracteres.
Llamamos a estos errores �scannos� (como �typos� en ingl�s-errata /
errores tipogr�ficos). Este reconocimiento equivocado puede crear
errores en el texto:</p>
<ul compact>
   <li>
Una palabra que parece correcta a la primera vista pero en verdad est�
mal deletreada, se puede detectar utilizando el corrector autom�tico de
ortograf�a.
   <li>
Una palabra cambiada por otra no menos valida pero no igual a la palabra
del texto original.
</ul>
<p>
Estos errores son sutiles porque no se pueden detectar de otra manera a
no ser leyendo el texto. Posiblemente el ejemplo m�s com�n del segundo
tipo es la palabra �and� reconocida por el OCR como �arid�. Otros
ejemplos: �eve� por �eye�, �Torn� por �Tom�, �train� por �tram�, �hab�a�
por �habla�, �cuidad� por �ciudad�. Es m�s dif�cil encontrar este tipo y
le damos un nombre especial: �Stealth Scannos�. Coleccionamos ejemplos
de �Stealth Scannos� en <a href="http://www.pgdp.net/phpBB2/viewtopic.php?t=1563">
este foro.</a> Los ejemplos de los scannos en espa�ol <a href="http://www.pgdpcanada.net/wiki/Scannos_en_espa%C3%B1ol">se encuentran aqu�</a>.
</p>
<p>Encontrar estos errores es m�s f�cil si utiliza una fuente
mono-espaciada como por ejemplo DPCustomMono o Courier que se puede
descargar haciendo clic en este
enlace: <a href="font_sample.php">DPCustomMono</a> o Courier.
</p>
<!-- END RR -->
<!-- More to be added.... -->

<h3><a name="hand_notes">Notas escritas a mano</a></h3>
<p>
No incluya notas escritas a mano que encuentre en el libro (a no ser que
se trate de texto original que alguien haya reforzado a mano para que se
vea mejor). No incluya notas escritas al margen por lectores del libro,
etc.
</p>


<h3><a name="bad_image">Im�genes de baja calidad</a></h3>
<p>
Si una imagen es de baja calidad (no se descarga, est� cortada,
ilegible), por favor env�e un mensaje al foro del proyecto. No haga clic
en �Return Page to Round� (devolver la p�gina a la ronda). Si lo hace la
p�gina se enviar� al pr�ximo revisor. En vez de eso, haga clic en
�Report Bad Page� (informar sobre la baja calidad de la imagen). La
p�gina se env�a a la cuarentena.
</p>
<p>
Observe que los archivos de algunas im�genes de las paginas son bastante
grandes, y puede ser normal que su navegador tenga problemas para
descargarlas, especialmente si tiene varios programas o ventanas
abiertos, o utiliza un ordenador viejo. Antes de reportar imagen de baja
calidad, intente hacer clic en �Image� (al lado de �View: Project
Comments� al fondo de la interfaz) y la p�gina se abrir� en una ventana
nueva. Si la imagen es de buena calidad, el problema ser� de su
ordenador o navegador y no de la imagen.
</p>
<p>
Es com�n que la imagen sea de buena calidad pero el OCR no ha le�do la
primera o primeras l�neas del texto. Por favor, teclee el texto que
falta. Si faltan casi todas las l�neas, puede teclearlas todas (si est�
de acuerdo con eso) o, hacer clic en �Return Page to Round�, y la p�gina
ser� devuelta a la misma ronda para que lo haga otra persona. Si
encuentra varias p�ginas as�, env�e un mensaje al foro (<a href="#forums">foros</a>) del proyecto y al
gerente del proyecto.
</p>

<h3><a name="bad_text">Imagen que no corresponde al texto</a></h3>

<p>Si la imagen no corresponde en absoluto al texto, mande un mensaje al
foro del proyecto (<a href="#forums">foros</a>). No haga clic en �Return Page to Round� (devuelve la
p�gina a la ronda). Si lo hace la p�gina va al pr�ximo revisor. En vez
de eso, haga clic en �Report Bad Page� (informar sobre la baja calidad
de la p�gina). La p�gina se pone en cuarentena.<hr>
</p>

<h3><a name="round1">Errores de revisores anteriores</a></h3>
<p>
Si un revisor anterior ha hecho, o se le escaparon muchos errores, por
favor t�mese un momento para enviarle un mensaje privado haciendo clic
en su nombre en la interfaz de revisi�n explicando c�mo tratar esta
situaci�n en el futuro.
</p>
<p>
Por favor, �<em>sea amable</em>! Todo el mundo aqu� es voluntario y (se supone)
est� intentando hacer lo mejor que puede. El objetivo de su mensaje debe
ser de informarle sobre la manera correcta de revisar y no de
criticarle. Muestre el punto concreto de su trabajo, y se�ale lo que
hizo y lo que ten�a que haber hecho.
</p>
<p>
Si el revisor anterior hizo un trabajo magn�fico, tambi�n puede enviarle
un mensaje dici�ndoselo&mdash;especialmente si se trata de una p�gina
dif�cil.<hr>
</p>

<h3><a name="p_errors">Errores de impresi�n / Ortograf�a</a></h3>
<p>
Corrija todos los errores del OCR (scannos, etc.) pero no corrija lo que
le parecen errores del impresor o de la ortograf�a (errores que tambi�n
se encuentran en la imagen del texto original). Muchos de los textos
antiguos tienen una manera diferente de deletrear y acentuar de la
actual, y nosotros mantenemos este estilo antiguo, incluyendo la
acentuaci�n.
</p>
<p>
Si no est� seguro ponga una nota en el texto as�:
  txet <tt>[**typo for text?]</tt>
y pregunte en el Foro del proyecto. Si cambia algo, incluya una nota que describa lo que ha cambiado, as�:
  <tt>[**Transcriber's Note: typo fixed, changed from "txet" to "text"]</tt>
Incluya los dos asteriscos <tt>**</tt> para llamar atenci�n del pos-procesor.
</p>

<h3><a name="f_errors">Errores de datos o hechos en el texto</a></h3>

<p>
Normalmente no corregimos errores de datos o hechos en los libros.
Muchos de los libros que revisamos contienen datos o declaraciones que
actualmente no son aceptables. D�jelos como los escribi� el autor del
libro.
</p>
<p>

Una excepci�n posible ser�a en algunos libros cient�ficos o t�cnicos,
donde una ecuaci�n o formula puede estar escrita incorrectamente,
(especialmente si aparece sin error en otras paginas del libro). Avise
al gerente del proyecto sobre este hecho, envi�ndole un mensaje privado
o un mensaje al foro del proyecto (<a href="#forums">Forum</a>). Tambi�n ponga en el texto una nota
tipo: <tt>[**es distinto de la en P�g. 123]</tt>.
</p>

<h3><a name="uncertain">Inseguridades</a></h3>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [...Est� por escribir...]
</p>

<H2 align=center>Fin de las reglas</H2>

<TABLE cellSpacing=0 width="100%" border=0>
 <TBODY>
 <TR>
   <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>
Volver <A
href="http://www.pgdpcanada.net/"> al la p�gina principal de Distributed
Proofreaders</A><BR><BR><BR>

<?
theme('','footer');
?>
