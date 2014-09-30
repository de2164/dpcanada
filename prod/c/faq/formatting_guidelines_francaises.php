<?

// Translated by user 'Pierre' at pgdp.net, 2006-02-08

$relPath='../pinc/';
include($relPath.'dpinit.php');
include($relPath.'faq.inc');
include($relPath.'pg.inc');
include($relPath.'theme.inc');
$no_stats=1;
theme('Directives de Formatage','header');

$utf8_site=!strcasecmp($charset,"UTF-8");
?>


<H1 align=center>Directives de Formatage</H1>
<H3 align=center>Version 1.9c, le 11 janvier 2006&nbsp;</H3>
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver><FONT size=+2><B>Table des
      mati�res</B></FONT></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI><A 
        href="#prime">La 
        r�gle principale </A>
        <LI><A 
        href="#summary">R�sum� 
        des directives</A> 
        <LI><A 
        href="#about">� 
        propos de ce document</A> 
        <LI><A 
        href="#comments">Commentaires 
        sur les projets</A> 
        <LI><A 
        href="#forums">Forum/Discuter 
        de ce Projet</A> 
        <LI><A 
        href="#prev_pg">Corriger 
        des erreurs sur les pages pr�c�dentes</A> </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver>
      <UL>
        <LI>Formatage de... </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI>&nbsp; 
        <UL>
          <LI><A 
          href="#title_pg">Page 
          de garde/fin</A> 
          <LI><A 
          href="#toc">Table 
          des mati�res</A> 
          <LI><A 
          href="#blank_pg">Page 
          blanche</A> 
          <LI><A 
          href="#page_hf">Ent�tes 
          et bas de page</A> 
          <LI><A 
          href="#chap_head">Ent�tes 
          de chapitres</A> 
          <LI><A 
          href="#sect_head">Ent�tes 
          de section</A> 
          <LI><A 
          href="#maj_div">Autres
          divisions dans les textes</A> 
          <LI><A 
          href="#para_side">Commentaires
          en marge des paragraphes</A> 
          <LI><A 
          href="#para_space">Espacement 
          et indentation des paragraphes</A> 
          <LI><A 
          href="#mult_col">Colonnes 
          multiples</A> 
          <LI><A 
          href="#illust">Illustrations</A> 

          <LI><A 
          href="#footnotes">Notes 
          de fin et de notes de bas de page</A> 
          <LI><A 
          href="#italics">Italiques</A> 

          <LI><A 
          href="#bold">Texte 
          gras</A> 
          <LI><A 
          href="#supers">Texte 
          en "Exposant"</A> 
          <LI><A 
          href="#subscr">Texte 
          en indice</A> 
          <LI><A 
          href="#underl">Texte 
          soulign�</A> 
          <LI><A
          href="#espace">T e 
          x t e&nbsp; e s p a c� (gesperrt) </A>
          <LI><A 
          href="#font_sz">Changement 
          de taille de police</A> 
          <LI><A 
          href="#word_caps">Mots 
          enti�rement en majuscules ou minuscules</A> 
          <LI><A 
          href="#small_caps">Petites
          capitales</A>           
          <LI><A 
          href="#lettrine">Lettre 
          de d�but de paragraphe grande ou orn�e </A>
          <LI><A 
          href="#em_dashes">Tirets, 
          traits d'union et signes 'moins'</A> 
          <LI><A 
          href="#eol_hyphen">Traits 
          d'union en fin de lignes</A> 
          <LI><A 
          href="#eop_hyphen">Traits 
          d'union en fin de page</A>
          <LI><A 
          href="#mots_isoles">Mots 
          isol�s en bas de page </A>

          <LI><A 
          href="#contract">Contractions</A> 

          <LI><A 
          href="#poetry">Po�sie/�pigrammes 
          </A>
          <LI><A 
          href="#letter">Indentation 
          de lettres (courrier)</A> 
          <LI><A 
          href="#lists">Listes 
          de choses</A> 
          <LI><A 
          href="#tables">Tableaux</A> 

          <LI><A 
          href="#block_qt">Blocs 
          de citation</A> 
          <LI><A 
          href="#double_q">Guillemets</A> 

          <LI><A 
          href="#single_q">Apostrophes 
          (simples quotes)</A> 
          <LI><A 
          href="#guill_chaque">Guillemets
          sur chaque ligne </A>
          <LI><A 
          href="#period_s">Points 
          entre les phrases</A> 
          <LI><A 
          href="#punctuat">Ponctuation</A> 

          <LI><A 
          href="#line_br">Retours 
          � la ligne</A> 
          <LI><A 
          href="#extra_sp">Espaces 
          suppl�mentaires entre les mots</A> 
          <LI><A 
          href="#trail_s">Espace 
          en fin de ligne</A> 
          <LI><A 
          href="#line_no">Num�ros 
          de ligne</A> 
          <LI><A 
          href="#extra_s">Espaces 
          suppl�mentaires, lignes et ast�risques entre les paragraphes</A> 
          <LI><A
          href="#period_p">Points 
          de suspension "..."</A> 
          <LI><A
          href="#a_chars">Charact�res 
          accentu�s et non-ASCII</A> 
          <LI><A 
          href="#char_diacr">Caract�res 
          avec marques diacritiques </A>
          <LI><A 
          href="#f_chars">Alphabets 
          non latins</A> 
          <LI><A 
          href="#fract_s">Fractions</A> 

          <LI><A 
          href="#page_ref">R�f�rences 
          aux pages "(Voir Pg. 123)"</A> 
          <LI><A 
          href="#bk_index">Index</A> 

          <LI><A 
          href="#play_n">Th��tre</A> 

          <LI><A 
          href="#anything">Tous 
          autres points n�cessitant un traitement particulier, ou dont vous 
          n'�tes pas s�r</A> </LI>
          <li><a href="#prev_notes">Notes des correcteurs pr�c�dents</a></li>
          </UL></LI></UL>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver>
      <UL>
        <LI>Guides sp�cifiques pour livres particuliers </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver>&nbsp;<BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI>&nbsp; 
        <UL>
          <LI><A 
          href="#sp_ency">Encyclop�dies</A> 

          <LI><A 
          href="#sp_poet">Po�sie</A> 

          <LI><A 
          href="#sp_chem">Chimie</A> 
          [� compl�ter.] 
          <LI><A 
          href="#sp_math">Math�matiques</A> 
          [� compl�ter.] </LI></UL></LI></UL></TD></TR>
  <P>&nbsp; 
  <UL></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=silver>
      <UL>
        <LI>Probl�mes courants </LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD align=left bgColor=white>
      <UL>
        <LI>&nbsp; 
        <UL>
          <LI><A 
          href="#OCR_1lI">Probl�mes 
          d'OCR "1-l-I"</A> 
          <LI><A 
          href="#OCR_0O">Probl�mes 
          d'OCR: 0-O</A> 
          <LI><A 
          href="#OCR_hyphen">Probl�mes 
          d'OCR: Tirets </A>
          <LI><A 
          href="#OCR_scanno">Probl�mes 
          d'OCR: Erreurs de scan</A> 
          <LI><A 
          href="#hand_notes">Notes 
          manuscrites dans le livre</A> 
          <LI><A 
          href="#bad_image">Mauvaises 
          images</A> 
          <LI><A 
          href="#bad_text">Image 
          ne correspondant pas au texte</A> 
          <LI><A 
          href="#round1">Erreurs 
          des correcteurs pr�c�dents</A>
          <LI><A 
          href="#p_errors">Erreurs 
          d'impressions et d'orthographe</A> 
          <LI><A 
          href="#f_errors">Erreurs 
          factuelles dans les textes</A> 
          <LI><A 
          href="#uncertain">Points 
          incertains</A> </LI></UL></LI></UL></TD></TR>
  <TR>
    <TD width=1 bgColor=silver><BR></TD>
    <TD bgColor=silver><BR></TD></TR></P></TBODY></TABLE>
<H3><A name=prime>La r�gle principale</A> </H3>
<P><EM>"Ne changez pas ce que l'auteur a �crit!"</EM> </P>
<P>Durant vos corrections, la r�gle principale � suivre est que le livre 
�lectronique final vu par un lecteur, potentiellement plusieurs ann�es dans le 
futur, doit <B>transmettre l'intention de l'auteur de mani�re exacte.</B> 
<BR></P>
<P>Donc, la r�gle g�n�rale est �<I>Ne changez pas ce que l'auteur a �crit</I>�. 
Si l'auteur �crit des mots d'une mani�re �trange, laissez-les. Si l'auteur �crit 
des choses choquantes, racistes ou partiales, laissez-les telles quelles. Si 
l'auteur semble mettre des italiques, des mots en gras ou des notes de bas de 
page tous les trois mots, gardez les italiques, les mots en gras et les notes de
bas de page.<BR></P>
<P>Par contre, nous changeons des choses mineures qui n'affectent pas le sens de 
ce que l'auteur a �crit. Nous rejoignons les mots s�par�s par un retour � la
ligne. (voir <A 
href="#eol_hyphen">Traits 
d'union en fin de lignes</A>) Ces changements nous permettent d'avoir des livres 
<EM>format�s d'une fa�on homog�ne</EM>. Nous suivons des r�gles de relecture 
pour avoir ce r�sultat. Lisez attentivement le reste de ces R�gles en gardant ce 
concept � l'esprit.</P>
<P>Pour aider le prochain formateur et le post-correcteur, nous gardons aussi 
les <A 
href="#line_br">retours � la 
ligne</A>. Il est ainsi facile de comparer les lignes du texte corrig� et les 
lignes de l'image. </P><!-- END RR -->
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>
<H3><A name=summary>R�sum� des directives</A> </H3>
<P>Le <A href="http://www.pgdpcanada.net/c/faq/formatting_summary.pdf">R�sum� des 
directives</A> est un court document imprimable de 2 pages (.pdf) qui r�sume les 
points principaux de ces directives, et qui donne des exemples de corrections. 
Les formateurs d�butants sont encourag�s � imprimer ce document et � le garder 
� port�e de main quand ils formatent. </P>
<P>Vous aurez besoin d'un lecteur de fichiers .pdf. Vous pouvez en t�l�charger 
un gratuitement chez Adobe� <A 
href="http://www.adobe.com/products/acrobat/readstep2.html">ici</A>. </P>
<H3><A name=about>� propos de ce document</A> </H3>
<P>Ce document a pour but de r�duire les diff�rences de formatage entre les 
travaux des diff�rents correcteurs qui ont travaill� sur un m�me livre, de 
mani�re � ce que nous formations tous <EM>de la m�me mani�re. </EM>Cela rend le 
travail plus facile aux post-correcteurs. Mais ce document n'est pas cens� �tre
un recueil de r�gles �ditoriales ou typographiques.<BR></P>
<P>Nous avons inclus dans ce document tous les points que les nouveaux 
utilisateurs ont demand� � propos du formatage et de la correction. S'il 
manque des points, ou que vous consid�rez que des points manquent, ou que des 
points devraient �tre d�crits de mani�re diff�rente ou si quelque chose est 
vague, merci de nous le faire savoir.</P>
<P>Ce document est un travail en �volution permanente. Aidez-nous � progresser en nous 
envoyant vos suggestions de changements sur le forum Documentation dans <A 
href="{$forums_url}/viewtopic.php?t=10779">ce thread</A>. </P>
<H3><A name=comments>Commentaires des projets</A> </H3>
<P>Dans la page d'interface dans laquelle vous commencez � formater des pages, 
il y a une section "Commentaires du projet" qui contient des informations 
sp�cifiques � ce projet (livre). <I>Lisez celles-ci avant de commencer � 
formater des pages!</I> Si le responsable de projet veut que vous formatiez 
quelque chose dans ce livre autrement que ce qui est dit dans ces directives, ce 
sera indiqu� l�. Les instructions dans les �Commentaires du projet� supplantent 
les r�gles dans ces directives, donc suivez-les.<BR>C'est aussi � cet endroit 
que le responsable de projet vous donne des informations int�ressantes � propos 
des livres, comme leur provenance, etc.</P>
<P><EM>Lisez aussi la discussion sur le projet</EM>: Le chef de projet y 
clarifie ds points portant sp�cifiquement sur le projet. Cette discussion est 
souvent utilis�e par les relecteurs pour signaler aux autres relecteurs les 
probl�mes r�currents dans le projet, et la meilleure fa�on de les r�soudre. </P>
<P>Sur la page Projet, le lien'Images, Pages Proofread, &amp; Differences' 
permet de voir comment les autres relecteurs ont chang� le texte. <A 
href="{$forums_url}/viewtopic.php?t=10217">Ce fil de discussion
</A>discute les diff�rentes fa�on d'utiliser cette information. </P>
<H3><A name=forums>Forum/Discuter de ce Projet</A> </H3>
<P>Dans la page d'interface dans laquelle vous commencez � formater des pages, 
sur la ligne �Forum�, il y a un lien indiquant �Discuter de ce projet� (si la 
discussion a d�j� commenc�) ou bien �D�marrer une discussion sur le projet� 
sinon. Cliquer sur ce lien vous am�nera � un "thread" de forum pour ce projet 
sp�cifique. C'est l'endroit pour poser des questions � propos de ce livre, 
informer le responsable de projet � propos de probl�mes, etc. L'utilisation de 
ce forum est la mani�re recommand�e pour discuter avec le responsable de projet 
et les autres correcteurs qui travaillent sur ce livre. </P>
<H3><A name=prev_pg>Corriger des erreurs sur des pages pr�c�dentes</A></H3>
<P>Quand vous s�lectionnez un projet pour travailler, la page <A 
href="#comments">Commentaires 
de projet</A> correspondant � ce projet est charg�e. </P>
<P>Cette page contient des liens vers les pages que vous avez corrig�es 
r�cemment (si vous n'avez pas encore corrig� de pages, alors aucun lien ne sera 
affich�).</P>
<P>Les pages list�es sous "DONE" et "IN PROGRESS" sont disponibles pour que vous 
puissiez corriger ou terminer votre travail de relecture. Cliquez sur le lien 
vers la page. Ainsi, si vous voyez que vous avez fait une erreur sur une page, 
vous pouvez cliquer sur cette page, et la rouvrir pour corriger l'erreur. </P>
<P> Il est �galement possible d'utiliser les liens 
"Images, Pages Proofread, &amp; Differences" ou l'option
"Just my pages". Ces pages pr�sentent un lien "Edit" sur
toutes les pages sur lesquelles vous avez travaill� durant
ce round. Il est encore temps de les corriger.</P>
<P>Pour plus de d�tails, voyez <A 
href="http://www.pgdpcanada.net/c/faq/prooffacehelp.php?i_type=0">Aide sur l'interface 
standard</A> ou bien <A 
href="http://www.pgdpcanada.net/c/faq/prooffacehelp.php?i_type=1">Aide sur l'interface 
avanc�e</A>, �a d�pend de l'interface que vous utilisez. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=6 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><FONT size=+2>Formatage 
de...</FONT></TD></TR></TBODY></TABLE>
<H3><A name=title_pg><B>Page de garde/fin</B></A> </H3>
<P>Laissez tout comme c'est imprim�, m�me si c'est tout en majuscules, ou en 
majuscules et minuscules, laissez aussi l'ann�e de publication et la copyright.
Certaines livres, souvent, mettent la premi�re lettre 
grande et orn�e. Tapez simplement la lettre.</P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Title Page Example" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image: </TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=520 alt="title page image" 
      src="http://www.pgdpcanada.net/c/faq/title.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>GREEN FANCY</TT> </P>
            <P><TT>BY</TT></P>
            <P><TT>GEORGE BARR McCUTCHEON</TT></P>
            <P><TT>AUTHOR OF "GRAUSTARK," "THE HOLLOW OF HER HAND,"<BR>"THE
            PRINCE OF GRAUSTARK," ETC.</TT></P>
            <P><TT>&lt;i&gt;WITH FRONTISPIECE BY&lt;/i&gt;<BR>&lt;i&gt;C. ALLAN 
            GILBERT&lt;/i&gt;</TT></P>
            <P><TT>NEW YORK<BR>DODD, MEAD AND COMPANY.</TT></P>
            <P><TT>1917</TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=toc>Table des mati�res</A> </H3>
<P>Laissez le texte de la table des mati�res comme il est imprim� (m�me si c'est 
tout en capitales). Encadrez la table par /* (pr�c�d�e d'une ligne blanche) au 
d�but et */ (suivie d'une ligne blanche) � la fin. Gardez les num�ros de page et
mettez-les 6 espaces apr�s la ligne de texte. Enlevez les points qui forment des 
lignes horizontales, entre le texte et le num�ro. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=TOC 
border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image: </TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%">
      <P><IMG height=650 alt="" src="http://www.pgdpcanada.net/c/faq/tablec.png" 
      width=500></P></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>CONTENTS</TT></P>
            <P><TT>/*<BR>CHAPTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAGE<BR><BR>I. THE FIRST 
            WAYFARER AND THE SECOND WAYFARER<BR>MEET AND PART ON THE 
            HIGHWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1<BR><BR>II. THE FIRST 
            WAYFARER LAYS HIS PACK ASIDE AND<BR>FALLS IN WITH 
            FRIENDS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15<BR><BR>III. MR. 
            RUSHCROFT DISSOLVES, MR. JONES INTERVENES,<BR>AND TWO MEN RIDE 
            AWAY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;35<BR><BR>IV. AN 
            EXTRAORDINARY CHAMBERMAID, A MIDNIGHT<BR>TRAGEDY, AND A MAN WHO SAID 
            "THANK YOU"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50<BR><BR>V. THE 
            FARM-BOY TELLS A GHASTLY STORY, AND AN<BR>IRISHMAN 
            ENTERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;67<BR><BR>VI. CHARITY 
            BEGINS FAR FROM HOME, AND A STROLL IN<BR>THE WILDWOOD 
            FOLLOWS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85<BR><BR>VII. SPUN-GOLD 
            HAIR, BLUE EYES, AND VARIOUS 
            ENCOUNTERS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;103<BR><BR>VIII. A 
            NOTE, SOME FANCIES, AND AN EXPEDITION IN<BR>QUEST OF 
            FACTS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;120<BR><BR>IX. THE FIRST 
            WAYFARER, THE SECOND WAYFARER, AND<BR>THE SPIRIT OF CHIVALRY
            ASCENDANT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;134<BR><BR>X. THE 
            PRISONER OF GREEN FANCY, AND THE LAMENT OF<BR>PETER THE 
            CHAUFFEUR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;148<BR><BR>XI. MR.
            SPROUSE ABANDONS LITERATURE AT AN EARLY<BR>HOUR IN THE 
            MORNING&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;167<BR><BR>XII. THE FIRST 
            WAYFARER ACCEPTS AN INVITATION, AND<BR>MR. DILLINGFORD BELABORS A 
            PROXY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;183<BR><BR>XIII. THE SECOND 
            WAYFARER RECEIVES TWO VISITORS 
            AT<BR>MIDNIGHT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;199<BR><BR>XIV. A 
            FLIGHT, A STONE-CUTTER'S SHED, AND A 
            VOICE<BR>OUTSIDE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;221<BR>*/<BR></TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=blank_pg>Page blanche</A> </H3>
<P>Merci de mettre comme texte <TT>[Blank Page]</TT>si le texte et l'image sont 
vides. Si le texte seulement (ou l'image seulement) est vide, suivez la 
proc�dure indiqu�es dans le cas d'une <A 
href="#bad_image">Mauvaise 
Image</A> ou d'un <A 
href="#bad_text">Mauvais 
Texte</A>.</P>
<H3><A name=page_hf>Ent�tes et bas de page</A> </H3>
<P>Enlevez les ent�tes et bas de page (mais pas les <A 
href="#footnotes">notes de 
bas de page</A>) du texte.</P>
<P>Ces ent�tes sont g�n�ralement sur la partie sup�rieure de l'image et ont un 
num�ro de page � leur oppos�. Les ent�tes peuvent �tre les m�mes au cours du 
livre (souvent le titre du livre et le nom de l'auteur); ils peuvent �tre 
identiques pour chaque chapitre (souvent le num�ro du chapitre); ou ils peuvent 
�tre diff�rents pour chaque page (d�crivant l'action sur cette page). 
Supprimez-les tous, quels qu'ils soient, en particulier le num�ro de page.</P><!-- END RR -->
<P>Un <A 
href="#chap_head">ent�te de 
chapitre </A>commence plus bas sur la page et n'a pas de num�ro de page sur la
m�me ligne. Laissez les ent�tes de chapitres en place -- voir exemple plus 
bas.</P><BR>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Page Headers and Footers" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=850 alt="" 
      src="http://www.pgdpcanada.net/c/faq/foot.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correct:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>/#<BR>In the United States?[A] In a railroad? In a mining 
            company?<BR>In a bank? In a church? In a college?<BR><BR>Write a 
            list of all the corporations that you know or have<BR>ever heard of, 
            grouping them under the heads &lt;i&gt;public&lt;/i&gt; and 
            &lt;i&gt;private&lt;/i&gt;.<BR><BR>How could a pastor collect his 
            salary if the church should<BR>refuse to pay it?<BR><BR>Could a bank
            buy a piece of ground "on speculation?" To<BR>build its 
            banking-house on? Could a county lend money if it<BR>had a surplus? 
            State the general powers of a corporation.<BR>Some of the special
            powers of a bank. Of a city.<BR><BR>A portion of a man's farm is 
            taken for a highway, and he is<BR>paid damages; to whom does said 
            land belong? The road intersects<BR>the farm, and crossing the road 
            is a brook containing<BR>trout, which have been put there and cared 
            for by the farmer;<BR>may a boy sit on the public bridge and catch 
            trout from that<BR>brook? If the road should be abandoned or lifted, 
            to whom<BR>would the use of the land 
            go?<BR>#/<BR><BR><BR><BR><BR>CHAPTER XXXV.<BR><BR>COMMERCIAL 
            PAPER.<BR><BR><BR>&lt;b&gt;Kinds and Uses.&lt;/b&gt;--If a man 
            wishes to buy some commodity<BR>from another but has not the money 
            to pay for<BR>it, he may secure what he wants by giving his 
            written<BR>promise to pay at some future time. This 
            written<BR>promise, or &lt;i&gt;note&lt;/i&gt;, the seller prefers 
            to an oral promise<BR>for several reasons, only two of which need be 
            mentioned<BR>here: first, because it is &lt;i&gt;prima 
            facie&lt;/i&gt; evidence of<BR>the debt; and, second, because it may 
            be more easily<BR>transferred or handed over to some one 
            else.<BR><BR>If J. M. Johnson, of Saint Paul, owes C. M. Jones,<BR>of 
            Chicago, a hundred dollars, and Nelson Blake, of<BR>Chicago, owes 
            J. M. Johnson a hundred dollars, it is<BR>plain that the risk, 
            expense, time and trouble of sending<BR>the money to and from 
            Chicago may be avoided,<BR><BR>[Footnote A: The United States: "Its 
            charter, the constitution. * * * Its flag the<BR>symbol of its 
            power; its seal, of its authority."--Dole.] 
    </TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=chap_head>Ent�tes de chapitres</A> </H3>
<P>Laissez les ent�tes de chapitres dans le texte tels qu'ils sont imprim�s.</P>
<P>Un ent�te de chapitre commence plus bas sur la page qu'un <A 
href="#page_hf">ent�te de
page</A> et n'a pas de num�ro de page sur la m�me ligne. Les ent�tes de 
chapitres sont souvent imprim�es enti�rement en majuscules, si c'est le cas, 
laissez-les tels quels.</P>
<P>Introduisez 4 lignes vierges avant le �CHAPITRE XXX� (ins�rez ces lignes 
blanches m�me si le chapitre d�marre sur une nouvelle page; il n'y a pas de 
pages sur un livre �lectronique, donc les lignes blanches sont n�cessaires). 
Laissez ensuite une ligne blanche entre chaque partie de l'ent�te du chapitre, 
comme la description du chapitre, ou une citation en ouverture, etc. et laissez 
2 lignes vierges apr�s, entre l'ent�te et le texte du chapitre.</P>
<P>Les vieux livres impriment souvent le premier mot de chaque chapitre 
enti�rement en majuscule; changez ces derniers en mots normaux (premi�re lettre 
seule en majuscule).</P>
<P>Faites attention � un guillemet ( " ) au d�but du premier paragraphe, 
que certains �diteurs n'incluaient pas ou que les OCR ignorent � cause de la 
grande majuscule dans l'original. Si l'auteur commence le paragraphe avec un 
dialogue, ins�rez le guillemet. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=Chapters 
border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=725 alt=""
      src="http://www.pgdpcanada.net/c/faq/chap1.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>GREEN FANCY<BR><BR><BR><BR><BR>CHAPTER I<BR><BR>THE FIRST 
            WAYFARER AND THE SECOND WAYFARER<BR>MEET AND PART ON THE 
            HIGHWAY<BR><BR><BR>A solitary figure trudged along the 
            narrow<BR>road that wound its serpentinous way<BR>through the 
            dismal, forbidding depths of<BR>the forest: a man who, though weary 
            and footsore,<BR>lagged not in his swift, resolute advance. 
            Night<BR>was coming on, and with it the no uncertain prospects<BR>of 
            storm. Through the foliage that overhung<BR>the wretched road, his 
            ever-lifting and apprehensive<BR>eye caught sight of the 
            thunder-black, low-lying<BR>clouds that swept over the mountain and 
            bore<BR>down upon the green, whistling tops of the trees.<BR><BR>At 
            a cross-road below he had encountered a small<BR>girl driving 
            homeward the cows. She was afraid<BR>of the big, strange man with 
            the bundle on his back<BR>and the stout walking stick in his hand: 
            to her a<BR>remarkable creature who wore "knee pants" 
            and<BR>stockings like a boy on Sunday, and hob-nail shoes,<BR>and a 
            funny coat with "pleats" and a belt, and a<BR>green hat with a 
            feather sticking up from the band. 
  </TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=sect_head>Ent�tes de section</A></H3>
<P>Dans certains livres, les chapitres sont divis�s en sections. Laissez les 
ent�tes de section comme ils sont imprim�s. Laissez deux lignes blanches avant 
cet ent�te, et une apr�s (� moins que le chef de projet en ait d�cid� 
autrement). Si vous ne savez pas si un ent�te d�marre un chapitre ou une
section, demandez dans la discussion de forum d�di�e au projet, en pr�cisant le 
num�ro de page. </P>
<H3><A name=maj_div>Autres divisions dans les textes</A></H3>
<P>Les autres grandes divisions des textes (Pr�face, Avant-propos, Introduction, 
Prologue, Epilogue, Appendice, R�f�rences, Conclusion, Glossaire, R�sum�, 
Remerciements, Bibliographie, etc.) seront trait�s comme des ent�tes de 
chapitre. Quatre lignes blanches avant l'ent�te, et deux avant le d�but du 
texte. </P>
<H3><A name=para_side>Commentaires de paragraphes</A> </H3>
<P>Certains livre ont de petites descriptions des paragraphes sur le c�t� du 
texte. Ce sont les "Sidenotes". D�placez ces notes juste au-dessus du paragraphe 
auquel elles appartiennent. Une Sidenote est entour�e par les marques de 
Sidenote: <TT>[Sidenote:&nbsp;</TT> avant et <TT>]</TT> apr�s. Corrigez la note 
pour qu'elle ressemble au texte imprim�, en gardant les retours � la ligne, les 
italiques, etc. Laissez une ligne blanche apr�s la note, pour qu'elle ne se 
m�lange pas avec le paragraphe durant la phase de post-correction.</P>
<P>S'il y a plusieurs notes pour un m�me paragraphe, mettez-les l'une apr�s 
l'autre au d�but du paragraphe. S�parez-les par des lignes blanches.</P>
<P>Si le paragraphe a commenc� sur une page pr�c�dente, mettez la note en haut 
de la page et marquez-la avec une ast�risque ( <TT>*</TT> ) de mani�re � ce que 
le post-correcteur puisse voir qu'elle appartient � la page pr�c�dente. De cette 
mani�re: <TT>*[Sidenote: le-commentaire-description]</TT> . Le post-correcteur 
d�placera la note � l'endroit appropri�.</P>
<P>Parfois, le chef de projet vous demandera de placer la note � c�t� de la 
phrase � laquelle elle s'applique, et pas au d�but ou � la fin du paragraphe. 
Dans ce cas, ne les s�parez pas par des lignes blanches.</P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=Sidenotes 
border=1>
  <COLGROUP>
  <COL width=128>
  <TBODY>
  <TR vAlign=top>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR vAlign=top>
    <TD align=left width="100%"><IMG height=800 alt="" 
      src="http://www.pgdpcanada.net/c/faq/side.png" width=550><BR></TD></TR>
  <TR vAlign=top>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR vAlign=top>
    <TD width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>*[Sidenote: Burning<BR>discs<BR>thrown into<BR>the 
            air.]<BR><BR>that such as looked at the fire holding a bit of 
            larkspur<BR>before their face would be troubled by no malady of 
            the<BR>eyes throughout the year.[1] Further, it was customary 
            at<BR>W�rzburg, in the sixteenth century, for the bishop's 
            followers<BR>to throw burning discs of wood into the air from a 
            mountain<BR>which overhangs the town. The discs were discharged 
            by<BR>means of flexible rods, and in their flight through the 
            darkness<BR>presented the appearance of fiery 
            dragons.[2]<BR><BR>[Sidenote: The Midsummer<BR>fires 
            in<BR>Swabia.]<BR><BR>[Sidenote: Omens<BR>drawn from<BR>the
            leaps<BR>over the<BR>fires.]<BR><BR>[Sidenote: 
            Burning<BR>wheels<BR>rolled<BR>down hill.]<BR><BR>In the valley of 
            the Lech, which divides Upper Bavaria<BR>from Swabia, the midsummer 
            customs and beliefs are, or<BR>used to be, very similar. Bonfires 
            are kindled on the<BR>mountains on Midsummer Day; and besides the 
            bonfire<BR>a tall beam, thickly wrapt in straw and surmounted by 
            a<BR>cross-piece, is burned in many places. Round this cross 
            as<BR>it burns the lads dance with loud shouts; and when 
            the<BR>flames have subsided, the young people leap over the fire 
            in<BR>pairs, a young man and a young woman together. If 
            they<BR>escape unsmirched, the man will not suffer from fever, 
            and<BR>the girl will not become a mother within the year. 
            Further,<BR>it is believed that the flax will grow that year as high 
            as<BR>they leap over the fire; and that if a charred billet be 
            taken<BR>from the fire and stuck in a flax-field it will promote 
            the<BR>growth of the flax.[3] Similarly in Swabia, lads and 
            lasses,<BR>hand in hand, leap over the midsummer bonfire, 
            praying<BR>that the hemp may grow three ells high, and they set 
            fire<BR>to wheels of straw and send them rolling down the 
            hill.<BR>Among the places where burning wheels were thus 
            bowled<BR>down hill at Midsummer were the Hohenstaufen 
            mountains<BR>in Wurtemberg and the Frauenberg near 
            Gerhausen.[4]<BR>At Deffingen, in Swabia, as the people sprang over
            the mid-*<BR><BR>[Footnote 1: &lt;i&gt;Op. cit.&lt;/i&gt; iv. i. p. 
            242. We have<BR>seen (p. 163) that in the sixteenth<BR>century these 
            customs and beliefs were<BR>common in Germany. It is also
            a<BR>German superstition that a house which<BR>contains a brand from 
            the midsummer<BR>bonfire will not be struck by lightning<BR>(J. W. 
            Wolf, &lt;i&gt;Beitr�ge zur deutschen<BR>Mythologie&lt;/i&gt;, i. p. 
            217, � 185).]<BR><BR>[Footnote 2: J. Boemus, &lt;i&gt;Mores, leges 
            et ritus<BR>omnium gentium&lt;/i&gt; (Lyons, 1541), 
            p.<BR>226.]<BR><BR>[Footnote 3: Karl Freiherr von 
            Leoprechting,<BR>&lt;i&gt;Aus dem Lechrain&lt;/i&gt; (Munich, 
            1855),<BR>pp. 181 &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, 
            &lt;i&gt;Der<BR>Baumkultus&lt;i&gt;, p. 510.]<BR><BR>[Footnote 4: A. 
            Birlinger, &lt;i&gt;Volksth�mliches aus<BR>Schwaben&lt;/i&gt; 
            (Freiburg im Breisgau, 1861-1862),<BR>ii. pp. 96 
            &lt;i&gt;sqq.&lt;/i&gt;, � 128, pp. 103<BR>&lt;i&gt;sq.&lt;/i&gt;, � 
            129; &lt;i&gt;id., Aus Schwaben&lt;/i&gt; (Wiesbaden,<BR>1874), ii. 
            116-120; E. Meier,<BR>&lt;i&gt;Deutsche Sagen, Sitten und 
            Gebr�uche<BR>aus Schwaben&lt;/i&gt; (Stuttgart, 1852), pp.<BR>423 
            &lt;i&gt;sqq.&lt;/i&gt;; W. Mannhardt, &lt;i&gt;Der 
            Baumkultus&lt;/i&gt;,<BR>p. 
  510.]<BR></TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=para_space>Espacement/Indentation des paragraphes</A> </H3>
<P>Mettez une ligne blanche avant tout d�but de paragraphe, m�me si ce 
paragraphe d�marre en haut d'une page. N'indentez pas le d�but des paragraphes 
(Mais si tous les paragraphes sont d�j� indent�s, ne prenez pas la peine 
d'enlever les espaces en trop --cela peut �tre fait facilement � la phase de 
post-correction).</P>
<P>Voyez l'image et le texte de la section <A 
href="#chap_head">Ent�tes de 
paragraphe</A> pour avoir un exemple. </P>
<H3><A name=mult_col>Colonnes Multiples</A> </H3>
<P>R�unissez les colonnes multiples en une seule colonne. Placez la colonne la
plus � gauche en premier puis les autres colonnes � sa suite. Vous ne devez rien 
faire de particulier pour marquer la s�paration des colonnes, mettez-les simplement 
ensemble. </P>
<P>Si le contenu des colonnes est une liste, mettez un /* avant le d�but de la 
liste et */ apr�s, pour �viter le regroupement des lignes pendant la phase de 
post-correction. Mettez une ligne vide avant le /* et une autre apr�s le */.</P>
<P>Voir aussi <A 
href="#bk_index">Index</A>, 
<A href="#lists">Listes</A> 
et <A 
href="#tables">Tables</A> 
</P>
<H3><A name=illust>Illustrations</A> </H3>
<P>Le texte pour une illustration doit �tre entour� de <TT>[Illustration: 
le-texte]</TT>. Gardez le texte comme il est imprim�, avec ses retours � la 
ligne, italiques, etc. </P>
<P>S'il n'y a pas de texte, indiquez juste <TT>[Illustration]</TT> � l'endroit 
o� elle se trouve. Si l'illustration est au milieu d'un paragraphe ou sur le 
c�t�, d�placez le <TT>[Illustration: le-texte] </TT>soit au-dessus, soit 
en-dessous du paragraphe, et mettez une ligne vide avant ou apr�s la marque 
d'illustration pour la s�parer du texte du paragraphe. Rejoignez les deux bouts 
du paragraphe qui �taient s�par�s par l'illustration en effa�ant les lignes 
vides.</P>
<P>Si le paragraphe coup� par l'illustration prend toute la page, ajoutez une 
<TT>*</TT> comme ceci: <TT>*[Illustration: <FONT color=red>(texte de 
l'illustration)</FONT>]</TT>, mettez-le tout en haut de la page, et laissez une
ligne vide apr�s. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary=Illustration border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image: </TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=525 alt="" 
      src="http://www.pgdpcanada.net/c/faq/illust.png" width=500> <BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>[Illustration: Martha told him that he had always been her 
            ideal and<BR>that she worshipped 
            him.<BR><BR>&lt;i&gt;Frontispiece&lt;/i&gt;<BR><BR>&lt;i&gt;Her 
            Weight in Gold&lt;/i&gt;] 
</TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Illustration au milieu d'un paragraphe" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image: (Illustration au milieu 
      d'un paragraphe)</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=514 alt="" 
      src="http://www.pgdpcanada.net/c/faq/illust2.png" width=500> <BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR vAlign=top>
    <TD>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>such study are due to Italians. Several of these 
            instruments<BR>have already been described in this journal, and on 
            the present<BR>occasion we shall make known a few others that 
            will<BR>serve to give an idea of the methods employed.<BR></TT></P>
            <P><TT>[Illustration: .&lt;sc&gt;Fig.&lt;/sc&gt;  1.--APPARATUS FOR THE STUDY OF 
            HORIZONTAL<BR>SEISMIC MOVEMENTS.]</TT></P>
            <P><TT>For the observation of the vertical and horizontal 
            motions<BR>of the ground, different apparatus are required. The</TT> 
            </P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=footnotes>Notes de bas de page et de fin</A> </H3>
<P>Les notes de bas de page sont "hors ligne", autrement dit, le texte de la 
note est en bas de la page et une marque est plac�e dans le texte, l� o� elle 
est r�f�renc�e. </P>
<P>Pour le formatage, ceci veut dire que : </P>
<P>1. Le num�ro, la lettre, le *, ou un autre caract�re qui marque la note doit 
�tre entour� de crochets (<TT>[</TT> et <TT>]</TT>). Enlevez les espaces avant
le <TT>[</TT>; mettez-le juste � c�t� du mot sur lequel porte la 
note<TT>[1]</TT> ou son signe de ponctuation,<TT>[2]</TT> comme dans le texte, 
et dans les deux exemples de cette phrase.</P>
<P>Parfois, les notes sont marqu�es par des s�ries de caract�res sp�ciaux (*, �, 
�, �, etc.) Dans ce cas, remplacez-les par des lettres majuscules, dans l'ordre 
(A,B,C, etc.) quand vous corrigez. </P>
<P>2.La note est entour�e par la marque de note <TT>[Footnote #:&nbsp;</TT> et 
<TT>]</TT>, avec le texte de la note entre les deux, et le num�ro (ou la lettre) 
de la note � la place du signe #. Laissez le texte de la note tel qu'il est 
imprim�, avec ses retours � la ligne, italiques, etc. Laissez la note en bas de 
la page. Utilisez bien la m�me marque de note dans la note et dans le texte (l� 
o� la note est r�f�renc�e). </P><!-- END RR -->
<P>Pour certains livres, le chef de projet vous demandera peut-�tre de mettre 
les notes de bas de page en ligne. Dans ce cas, lisez les "Commentaires de 
projet". </P>
<P>Pour avoir un exemple de note de bas de page, voyez l'exemple de la section 
<A href="#page_hf">Ent�tes 
et bas de page</A> . </P>
<P>Si vous voyez une note en bas d'une page, sans marque de note dans le texte, 
surtout si elle d�marre au milieu d'une phrase ou d'un mot, c'est probablement 
la continuation d'une note de bas de page de la page pr�c�dente. Laissez-la au 
bas de la page, avec les autres notes de bas de page, et entourez-la par 
<TT>*[Footnote: <FONT color=red>(texte de la note)</FONT>]</TT> (sans marque ou 
num�ro de note). L'�toile indique que c'est une continuation de note, et attire 
l'attention du post-correcteur. </P>
<P>Si une note continue sur la page suivante (la page s'arr�te avant la note), 
laissez la note � la fin de la page, et mettez un ast�risque * l� o� la note 
s'arr�te, comme ceci <TT>[Footnote 1: <FONT 
color=red>texte-texte-texte</FONT>]*</TT>. (Le * indique que la note s'arr�te
pr�matur�ment, et attire l'attention du post-correcteur qui fusionnera les deux 
parties de la note. </P>
<P>Si une note fragment�e sur plusieurs pages commence ou s'arr�te sur un mot 
coup�, marquez le mot coup� <B>et</B> la note par une �toile, comme ceci. <br>
<TT>[Footnote 1: Cette note se poursuit et son dernier mot se poursuit 
aus-*]*</TT> pour le premier fragment, et <br><TT>*[Footnote: *si sur la page 
suivante.] <br></TT> pour le second fragment.</P>
<P>Si une note est r�f�renc�e dans le texte mais n'appara�t pas sur cette page, 
laissez la marque de note entour�e de crochets, comme d'habitude. Ce cas est 
courant dans les livres scientifiques et techniques, o� les notes sont souvent 
group�es en fin de chapitre. </P>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Footnote Examples" border=1>
  <TBODY>
  <TR>
    <TH vAlign=top align=left bgColor=cornsilk>Texte original:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>The principal persons involved in this argument were 
            Caesar<SUP>1</SUP>, former military<BR>leader and Imperator, and the
            orator Cicero<SUP>2</SUP>. Both were of the 
            aristocratic<BR>(Patrician) class, and were quite wealthy.<BR>
            <HR align=left width="50%" noShade SIZE=2>
            <FONT size=-1><SUP>1</SUP> Gaius Julius Caesar.</FONT><BR><FONT 
            size=-1><SUP>2</SUP> Marcus Tullius Cicero.</FONT> 
      </TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TH vAlign=top align=left bgColor=cornsilk>Corrig� avec notes de bas de 
      page</TH></TR>
  <TR vAlign=top>
    <TD>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>The principal persons involved in this argument were 
            Caesar[1], former military</TT><BR><TT>leader and Imperator, and the 
            orator Cicero[2]. Both were of the 
            aristocratic</TT><BR><TT>(Patrician) class, and were quite 
            wealthy.</TT><BR><BR><TT>[Footnote 1: Gaius Julius 
            Caesar.]</TT><BR><BR><TT>[Footnote 2: Marcus Tullius Cicero.]</TT> 
          </TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<P>Pour certains livres, les notes de bas de page sont s�par�es du texte 
principal par une ligne horizontale. Nous ne le ferons pas; laissez donc juste 
une ligne blanche entre le texte et la note (voir exemple ci-dessus).</P>
<P>Les <B>notes de fin</B> sont simplement des notes de bas de page qui ont �t� 
plac�es en fin de chapitre, ou en fin de livre, au lieu d'�tre en fin de page. 
Traitez-les comme des notes hors-ligne. Quand vous voyez la r�f�rence dans le 
texte, entourez-la par des crochets. Si vous corrigez une des pages de fin, l� 
o� sont les notes,&nbsp; entourez la note par <TT>[Footnote #: <FONT 
color=red>(texte)</FONT>] </TT>en rempla�ant le signe # par le num�ro ou la 
marque de la note. Mettez une ligne blanche apr�s chaque note, pour qu'elles 
apparaissent comme des paragraphes s�par�s.</P>
<P>Les notes sur de la po�sie, ou sur des tables sont trait�es comme les autres. 
Marquez la r�f�rence, et laissez les notes en bas de la page. Le post-correcteur 
d�cidera de leur emplacement final.&nbsp;</P>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=Footnotes 
border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Po�sie annot�e</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>Mary had a little lamb<SUP>1</SUP><BR>&nbsp;&nbsp;&nbsp;Whose 
            fleece was white as snow<BR>And everywhere that Mary 
            went<BR>&nbsp;&nbsp;&nbsp;The lamb was sure to go!<BR>
            <HR align=left width="50%" noShade SIZE=2>
            <FONT size=-2><SUP>1</SUP> This lamb was obviously of the Hampshire 
            breed,<BR>well known for the pure whiteness of their wool.</FONT> 
        </TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>/*<BR>Mary had a little lamb[1]<BR>&nbsp;&nbsp;Whose fleece 
            was white as snow<BR>And everywhere that Mary 
            went<BR>&nbsp;&nbsp;The lamb was sure to go!<BR>*/<BR><BR>[Footnote 
            1: This lamb was obviously of the Hampshire breed,<BR>well known for 
            the pure whiteness of their 
  wool.]<BR></TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=italics>Italiques</A> </H3>
<P>Le texte en italique doit avoir <TT>&lt;i&gt;</TT> ins�r� avant et 
<TT>&lt;/i&gt;</TT> ins�r� � la fin de l'italique. (remarquez le �<TT>/</TT>� 
dans le symbole de fin).&nbsp;<BR>La ponctuation va HORS de l'italique, � moins 
que ce signe ne soit dans une phrase ou une section enti�re qui est en italique,
ou alors si le signe fait partie d'une phrase, titre ou abr�viation qui
est en italique. 
Par exemple, le <TT>.</TT> qui signale l'abr�viation dans le titre d'un journal 
comme <I>Phil. Trans.</I> est entre les marques d'italiques. D'o� : 
<TT>&lt;i&gt;Phil. Trans.&lt;/i&gt;</TT><BR>Voyez l'image de la section <A 
href="#illust">Illustration</A> 
pour un exemple de la mani�re de faire les italiques.</P>
<P>Certaines polices, en particulier les plus vieilles utilisaient les m�mes 
symboles pour les nombres en italique et non italique. Donc, pour les dates et 
phrases similaires, marquez la phrase enti�re en italique plut�t que de 
marquer les mots en italique et les nombres en non italique.</P>
<P>Si une phrase en italique est une s�rie de mots ou de noms, mettez chacun 
d'eux en italiques (et pas la phrase dans son ensemble). </P><!-- END RR -->
<P><B>Exemples</B>�Italiques: </P>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=Italics 
border=1>
  <TBODY>
  <TR>
    <TH vAlign=top bgColor=cornsilk>Texte original:</TH>
    <TH vAlign=top bgColor=cornsilk>Texte corrig�:</TH></TR>
  <TR>
    <TD vAlign=top><I>Enacted </I>4<I> July, </I>1776 </TD>
    <TD vAlign=top><TT>&lt;i&gt;Enacted 4 July, 1776&lt;/i&gt;</TT> </TD></TR>
  <TR>
    <TD vAlign=top><I>God knows what she saw in me!</I> I spoke<BR>in such an 
      affected manner.</TD>
    <TD vAlign=top><TT>&lt;i&gt;God knows what she saw in me!&lt;/i&gt; I 
      spoke<BR>in such an affected manner.</TT></TD></TR>
  <TR>
    <TD vAlign=top>As in many other of these <I>Studies</I>, and</TD>
    <TD vAlign=top><TT>As in many other of these &lt;i&gt;Studies&lt;/i&gt;, 
      and</TT></TD></TR>
  <TR>
    <TD vAlign=top>(<I>Psychological Review</I>, 1898, p. 160)</TD>
    <TD vAlign=top><TT>(&lt;i&gt;Psychological Review&lt;/i&gt;, 1898, p. 
      160)</TT></TD></TR>
  <TR>
    <TD vAlign=top>L. Robinson, art. "<I>Ticklishness</I>,"</TD>
    <TD vAlign=top><TT>L. Robinson, art. 
      "&lt;i&gt;Ticklishness&lt;/i&gt;,"</TT></TD></TR>
  <tr>
      <td valign="top" align="right"><i>December</i> 3, <i>morning</i>.<br />
                     1323 Picadilly Circus</td>
      <td valign="top"><tt>/*<br />
         &lt;i&gt;December 3, morning.&lt;/i&gt;<br />
         1323 Picadilly Circus<br />
         */</tt></td>
  </tr>    
  <TR>
    <TD vAlign=top>Proofreaders may be tickled pink to
      read<BR><I>Ticklishness</I>, <I>Tickling and Laughter</I>,<BR><I>Remarks 
      on Tickling and Laughter</I><BR>and <I>Ticklishness, Laughter and 
      Humour</I>. </TD>
    <TD vAlign=top><TT>Proofreaders may be tickled pink to 
      read<BR>&lt;i&gt;Ticklishness&lt;/i&gt;, &lt;i&gt;Tickling and 
      Laughter&lt;/i&gt;,<BR>&lt;i&gt;Remarks on Tickling and 
      Laughter&lt;/i&gt;<BR>and &lt;i&gt;Ticklishness, Laughter and 
      Humour&lt;/i&gt;.</TT> </TD></TR></TBODY></TABLE>
<H3><A name=bold>Texte gras</A> </H3>
<P>Le texte gras doit �tre marqu� par <TT>&lt;b&gt;</TT> avant et <TT>&lt;/b&gt; 
</TT>apr�s.</P>
<P>Les signes de ponctuation doivent �tre HORS des marques de "gras", � moins 
que ces signes ne soient dans une phrase ou une partie de phrase enti�rement en 
gras. </P>
<P>Voyez l'exemple de la section <A 
href="#page_hf">Ent�tes et 
bas de page</A> . </P>
<P>Certains chefs de projet peuvent sp�cifier dans les commentaires de projet 
que le texte gras doit �tre rendu par des majuscules. </P>
<H3><A name=supers>Exposants</A> </H3>
<P>Les vieux livres abr�geaient souvent les mots en contractions, et les 
imprimaient en exposant, par exemple:<FONT color=red> <BR>Gen<SUP>rl</SUP> 
Washington defeated L<SUP>d</SUP>Cornwall's army.<BR></FONT>Ins�rez un chapeau 
pour identifier l' abr�viation/contraction, comme suit: 
<BR>&nbsp;&nbsp;&nbsp;&nbsp; <TT>Gen^rl Washington defeated L^d Cornwall's 
army.</TT> </P>
<P>Dans les ouvrages scientifiques et techniques utilisez le "chapeau" 
<TT>^</TT> et mettez le texte en exposant entre accolades <TT>{</TT> et 
<TT>}</TT>. Mettez toujours le texte en exposant entre accolades, m�me si ce 
texte ne fait qu'un caract�re. Ainsi: <BR>&nbsp; &nbsp; &nbsp; &nbsp; ... up to 
x<SUP>n-1</SUP> elements in the array. <BR>donne <BR>&nbsp; &nbsp; &nbsp; &nbsp; 
<TT>... up to x^{n-1} elements in the array.<BR></TT></P>
<P>Si le chef de projet dit de faire autrement dans les commentaires de projet, 
suivez ses instructions. </P>
<H3><A name=subscr>Texte en Indice</A></H3>
<P>On trouve la notation "indice" dans des ouvrages scientifiques, rarement 
ailleurs. Indiquez l'indice en mettant un signe "soulign�" <TT>_</TT> devant et 
en entourant le texte en indice avec des accolades <TT>{</TT> et <TT>}</TT>. 
<BR>Par exemple: <BR>&nbsp; &nbsp; &nbsp; &nbsp; H<SUB>2</SUB>O. <BR>donne 
<BR>&nbsp; &nbsp; &nbsp; &nbsp; <TT>H_{2}O.<BR></TT></P>
<H3><A name=underl>Texte soulign�</A> </H3>
<P>Marquez le texte soulign� comme �tant de l'<A 
href="#italics">Italique</A>, 
avec <TT>&lt;i&gt;</TT> et <TT>&lt;/i&gt;</TT>, � moins que les <A 
href="#comments">Commentaires 
de projet</A> sp�cifient l'utilisation de <TT>&lt;u&gt; </TT>et&nbsp; 
<TT>&lt;/u&gt; </TT>pour ce livre. Souligner �tait utilis� pour indiquer un 
italique quand l'�diteur �tait incapable d'italiciser un texte, par exemple, 
pour un document tap� � la machine. </P>
<H3><A name=espace>T e x t e&nbsp;&nbsp; e s p a c � (gesperrt)</A> </H3>
<P>Marquez ce texte en italique avec <TT>&lt;i&gt;</TT> et <TT>&lt;/i&gt; 
</TT>et enlevez les espaces en trop.&nbsp; Cette technique �tait utilis�e pour 
mettre l'accent sur certains passages sur certains vieux livres allemands, et 
parfois italiens. Maintenant, cette fonction est remplie par les italiques, et 
les espaces suppl�mentaires peuvent ne pas �tre clairs sur certains �crans, avec
certaines fontes, quand les lecteurs liront le texte �lectronique. </P>
<H3><A name=#font_sz>Changement de taille de police</A> </H3>
<P>Ne faites rien pour indiquer un changement de taille de police. L'exception �
ceci est lorsque la taille de la police change pour indiquer un <A 
href="#block_qt">bloc de 
citation</A>; dans ce cas marquez le texte comme c'est sp�cifi� pour ces cas-l�.</P>

<H3><A name=word_caps>Mots entiers en majuscules</A> </H3>
<P>Si un mot ou groupe de mots dans un texte est imprim� enti�rement en 
majuscules, laissez-les tels qu'ils sont dans votre 
copie de travail. </P>
<P>Une exception � cette r�gle est le <A 
href="#chap_head">premier 
mot d'un chapitre ou d'un paragraphe</A>: Certains livres anciens mettaient le 
premier mot de chaque paragraphe en majuscule; ce doit �tre chang� en un mot 
normal (premi�re lettre en majuscule, le reste en minuscule). Donc "IL �tait une 
fois" devient "Il �tait une fois". </P>
<H3><A name=small_caps>Petites capitales</A></H3>
      <P>Corrigez les mots en  <SPAN 
      style="FONT-VARIANT: small-caps">Petites capitales altern�es</SPAN> en faisant 
      alterner minuscules et majuscules et entourez le texte par les marques<TT>&lt;sc&gt;</TT> et 
      <TT>&lt;/sc&gt;</TT>. Exemple: <SPAN 
      style="FONT-VARIANT: small-caps">This is Small Caps</SPAN> devient 
      <TT>&lt;sc&gt;This is Small Caps&lt;/sc&gt;</TT>. </P>
<P>Mais si un mot est imprim� <span style="font-variant: small-caps;">
tout en petites capitales </span>, alors �crivez-le en 
CAPITALES, et entourez-le de marques <tt>&lt;sc&gt;</tt>
et <tt>&lt;/sc&gt;</tt>.
   <br>
&nbsp;&nbsp;&nbsp;&nbsp;Exemple:
   You cannot be serious about
   <span style="font-variant: small-caps;">aardvarks</span>!<br>
&nbsp;&nbsp;&nbsp;&nbsp;devient:
   <tt>You cannot be serious about
   &lt;sc&gt;AARDVARKS&lt;/sc&gt;!</tt> <br>
</p>

<p>Si un mot est en capitales dans un titre (un ent�te de chapitre, ou de section), laissez-le
en capitales, sans marques <tt>&lt;sc&gt;</tt> et <tt>&lt;/sc&gt;</tt>. <br>
Si le premier mot d'un chapitre est en capitales, alors
changez-le en majuscules et minuscules, sans marques de petites capitales. </P>
<H3><A name=lettrine>Lettre de d�but de paragraphe grande ou orn�e.</A> </H3>
<P>Souvent, la premi�re lettre d'un chapitre, section ou paragraphe est imprim�e 
tr�s grande et orn�e (une lettrine). Dans votre texte, laissez simplement la 
lettre. </P>
<H3><A name=em_dashes>Tirets, traits d'unions, et signe �moins�</A></H3>
<P>Vous verrez quatre types de traits dans les livres.</P>
<OL compact>
  <LI>Les tirets "<I>hyphens</I>". Ils sont utilis�s pour <B>joindre</B> les
  mots, ou parfois pour joindre les pr�fixes ou les suffixes � un mot. Dans 
  votre texte corrig�, laissez un seul tiret, sans espace ni � droite ni � 
  gauche. 
<br>Notez l'exception � cette r�gle, dans le deuxi�me exemple
ci-dessous. </LI>
  <LI>Les tirets longs. "<I>En-dashes</I>". Ils sont un peu plus longs, ils sont 
  utilis�s pour des <B>intervalles</B> de nombres, ou pour le signe math�matique 
  "moins". L� aussi, laissez un seul tiret. Laissez un espace avant ou apr�s 
  selon la fa�on dont c'est imprim� sur le livre. En g�n�ral, pas d'espace pour 
  les intervalles de nombres, mais, autour du signe "moins", il y en a parfois 
  des deux c�t�s, parfois seulement avant. </LI>
  <LI>Les tirets <I>Em-dashes &amp; long dashes. </I>Ils servent de 
  <B>s�parateurs</B> entre les mots�parfois pour mettre l'accent, comme ceci�ou 
  quand une personne prend la parole, ou s'interrompt dans un dialogue. 
  Notez-les comme deux tirets. Sans espace ni avant ni apr�s, m�me s'il semble y 
  en avoir un sur le document imprim�. </LI>
  <LI>Les traits qui repr�sentent des mots (ou des noms) <B>omis</B> 
  ou <B>censur�s</B>. Notez-les comme quatre tirets. Si le long trait repr�sente 
  un mot, laissez des espaces autour des tirets, comme si c'�tait vraiment le 
  mot. Si c'est seulement une partie de mot, alors pas d'espaces. Joignez-le au 
  reste du mot.</LI></OL>
<P>Note. Si un tiret em-dash appara�t au d�but (ou � la fin) de votre ligne dans 
votre texte, joignez-le � l'autre ligne pour qu'il n'y ait pas d'espace autour 
du tiret. C'est seulement si l'auteur a utilis� un "dash" au d�but ou � la fin 
d'un paragraphe, ou sur une ligne de po�sie, ou un dialogue que vous devez le
laissez au d�but ou � la fin de la ligne.</P><!-- END RR -->
<P>Quelques exemples.&nbsp;</P>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center
summary="Hyphens and Dashes" border=1>
  <TBODY>
  <TR>
    <TH vAlign=top bgColor=cornsilk>Image de d�part:</TH>
    <TH vAlign=top bgColor=cornsilk>Texte correctement format�:</TH>
    <TH vAlign=top bgColor=cornsilk>Type</TH></TR>
    <tr>
      <td valign="top">semi-detached</td>
      <td valign="top"><tt>semi-detached</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">three- and four-part harmony</td>
      <td valign="top"><tt>three- and four-part harmony</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">discoveries which the Crus-<br>
        aders made and brought home with</td>
      <td valign="top"><tt>discoveries which the Crusaders<br>
        made and brought home with</tt></td>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">factors which mold char-<br>
        acter&mdash;environment, training and heritage,</td>
      <td valign="top"><tt>factors which mold character--environment,<br>
        training and heritage,</tt>
      <td> Hyphen</td>
    </tr>
    <tr>
      <td valign="top">See pages 21&ndash;25</td>
      <td valign="top"><tt>See pages 21-25</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">&ndash;14&deg; below zero</td>
      <td valign="top"><tt>-14&deg; below zero</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">X &ndash; Y = Z</td>
      <td valign="top"><tt>X - Y = Z</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">2&ndash;1/2</td>
      <td valign="top"><tt>2-1/2</tt></td>
      <td>En-dash</td>
    </tr>
    <tr>
      <td valign="top">I am hurt;&mdash;A plague<br> on both your houses!&mdash;I am dead.</td>
      <td valign="top"><tt>I am hurt;--A plague<br> on both your houses!--I am dead.</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">sensations&mdash;sweet, bitter, salt, and sour<br>
        &mdash;if even all of these are simple tastes. What</td>
      <td valign="top"><tt>sensations--sweet, bitter, salt, and sour--if<br>
        even all of these are simple tastes. What</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">senses&mdash;touch, smell, hearing, and sight&mdash;<br>
        with which we are here concerned,</td>
      <td valign="top"><tt>senses--touch, smell, hearing, and sight--with<br>
        which we are here concerned,</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">It is the east, and Juliet is the sun!&mdash;</td>
      <td valign="top"><tt>It is the east, and Juliet is the sun!--</tt></td>
      <td>Em-dash</td>
    </tr>
    <tr>
      <td valign="top">"Three hundred&mdash;&mdash;" "years," she was going to
	say, but the left-hand cat interrupted her.</td>
      <td valign="top"><tt>"Three hundred----" "years," she was going to
	say, but the left-hand cat interrupted her.</tt></td>
      <td>Longer Em-dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. &mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. ---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">As the witness Mr. S&mdash;&mdash; testified,</td>
      <td valign="top"><tt>As the witness Mr. S---- testified,</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">the famous detective of &mdash;&mdash;B Baker St.</td>
      <td valign="top"><tt>the famous detective of ----B Baker St.</tt></td>
      <td>long dash</td>
    </tr>
    <tr>
      <td valign="top">&ldquo;You &mdash;&mdash; Yankee&rdquo;, she yelled.</td>
      <td valign="top"><tt>"You ---- Yankee", she yelled.</tt></td>
      <td>long dash</td>
    </tr>
    </TBODY></TABLE>
<H3><A name=eol_hyphen>Traits d'union en fin de ligne</A> </H3>
<P>Enlevez le trait d'union en fin de ligne et collez les deux morceaux du mot
qui �tait coup�. A moins que ce ne soit r�ellement un mot avec tiret tel que 
porte-manteau. Mais si le mot �tait coup� parce que la ligne est trop courte, et 
non pas parce qu'il prend g�n�ralement un trait d'union, alors rejoignez les
deux parties. Laissez le mot sur la ligne sup�rieure et ins�rez un retour � la 
ligne apr�s ce mot pour conserver le formatage des lignes--cela rend la t�che 
plus facile au correcteur du second tour. Voyez � <A 
href="#em_dashes">Tirets, 
traits d'unions, et signe �moins�</A> pour des exemples de chaque type 
(<TT>nar-row</TT> est transform� en <TT>narrow</TT>, mais <TT>low-lying</TT> 
garde le tiret). Si le mot coup� est suivi d'un signe de ponctuation, mettez ce 
signe sur la premi�re ligne aussi.</P>
<P>Laissez le trait d'union aux mots qui s'�crivaient anciennement avec un trait 
d'union mais qui n'en ont plus aujourd'hui. Si vous n'�tes pas s�r de savoir si 
l'auteur a mis un tiret ou non, laissez le tiret, mettez un * apr�s, et 
rejoignez les deux parties du mot. Comme ceci : <TT>to-*day</TT>. L'ast�risque 
attirera l'attention du post-correcteur, qui a acc�s � toutes les pages et qui 
verra comment l'auteur �crit habituellement le mot.</P>
<H3><A name=eop_hyphen>Traits d'union en fin de page</A> </H3>
<P>Laissez le trait d'union � la fin de la derni�re ligne, mais marquez le avec 
un ast�risque ( <TT>*</TT>) apr�s le trait d'union de mani�re � permettre au 
post-correcteur de le remarquer plus facilement. <BR>Par exemple, 
corrigez:<BR>&nbsp;<BR>&nbsp; &nbsp; &nbsp; &nbsp;something Pat had already 
become accus-<BR>par:<BR>&nbsp; &nbsp; &nbsp; &nbsp;<TT>something Pat had 
already become accus-*</TT> </P>
<P>Pour les pages qui commencent avec un mot commenc� � la fin de la page 
pr�c�dente (ou un em-dash), placez un <TT>*</TT> avant le mot.<BR>Pour continuer avec l'exemple
ci-dessus, corrigez:<BR>&nbsp;<BR>&nbsp; &nbsp; &nbsp; &nbsp;tomed to from 
having to do his own family<BR>en:<BR>&nbsp; &nbsp; &nbsp; &nbsp;<TT>*tomed to 
from having to do his own family</TT> </P>
<P>Ces signes indiquent au post-correcteur, quand il produit le texte final, 
qu'il doit rejoindre les deux parties du mot. </P>
<H3><A name=mots_isoles>Mots isol�s en bas de page</A> </H3>
<P>Effacez ces mots. M�me si c'est la seconde partie d'un mot coup�.</P>
<P>Dans certains vieux livres, vous verrez un mot isol� en bas de page, pr�s de 
la marge de droite. C'est le premier mot de la page suivante (un "incipit"). 
C'�tait pour indiquer � l'imprimeur quel �tait le verso correct de la page. Ca 
facilitait la t�che aux aides de l'imprimeur qui pr�paraient les pages avant la 
reliure. Le lecteur lui-m�me n'avait pas besoin de tourner plus d'une page. </P><!-- END RR -->

<H3><A name=contract>Contractions</A> </H3>
<P>En anglais, enlevez les espaces des contractions. Par exemple: <TT>would 
n't</TT> devrait �tre <TT>wouldn't</TT>. (C'�tait une convention utilis�e pour 
indiquer que would et not �taient originellement deux mots s�par�s.) Parfois 
aussi, c'est une erreur d'OCR, enlevez l'espace en trop dans tous les cas. </P>
<P>Certains chefs de projet recommanderont dans les <A 
href="#comments">commantaires 
de projet </A>de ne pas enlever les espaces dans les contractions, en 
particulier dans des textes �crits en dialecte, argot ou une langue autre que 
l'anglais.</P>
<H3><A name=poetry>Po�sie/�pigrammes</A> </H3>
<P>Cette section s'applique aux po�mes et aux �pigrammes dans un livre qui n'est 
pas un livre de po�sie. Pour les livres de po�sie, voir les <A
href="http://www.pgdpcanada.net/c/faq/doc-poet.php">Directives sp�ciales pour les 
livres de po�sie.</A> </P>
<P>Marquez les po�sies ou les �pigrammes de sorte que le responsable puisse les 
trouver plus vite. Ins�rez une nouvelle ligne avec un <TT>/*</TT> au d�but de la 
po�sie ou �pigramme et une ligne s�par�e avec <TT>*/</TT> � la fin. Mettez une 
ligne blanche avant <TT>/*</TT> et une autre apr�s <TT>*/</TT>. </P>
<P>Pr�servez l'indentation relative des vers les uns par rapport aux autres, en 
ajoutant 2,4,6... espaces avant le d�but du vers de fa�on � ressembler au texte 
imprim�. </P>
<P>Quand un vers est trop long pour la page, la plupart des �ditions cassent le 
vers et impriment la fin sur une ligne s�par�e, vers la marge de droite. Dans 
ces cas-l�, rejoignez les deux parties du vers de fa�on � ne former qu'une 
ligne. Les lignes de continuation commencent souvent par une minuscule, et 
apparaissent irr�guli�rement alors que l'indentation normale appara�t 
r�guli�rement au cours du po�me. </P>
<P>Ne passez pas votre temps � centrer les lignes de po�sie, m�me si elles sont 
centr�es sur la page originale. Calez le texte sur la marge de gauche (en 
indentant les vers les uns par rapport aux autres si n�cessaire). </P>
<P>Les notes de bas de page dans la po�sie se traitent comme les autres notes de 
bas de page. Voyez <A 
href="#footnotes">notes de 
bas de page</A> pour plus de details.</P>
<P>Gardez les num�ros de vers s'ils sont imprim�s. Mettez-les � la fin de la
ligne, et s�parez-les du texte par 6 blancs au moins. Voir <A 
href="#line_no">Num�ros de 
ligne </A>pour des d�tails. </P>
<P>Regardez les <A 
href="#comments">commentaires 
de projet </A>pour des instructions sp�cifiques. Souvent, les livres de po�sie 
ont des instructions sp�cifiques. Souvent, dans les livres compos�s enti�rement 
ou principalement de po�sie, ces instructions ne s'appliqueront pas. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Poetry Example" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TH vAlign=top width="100%"><IMG height=508 alt="" 
      src="http://www.pgdpcanada.net/c/faq/poetry.png" width=500> <BR></TH></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>to the scenery of his own country:<BR></TT>
            <P><TT>/*<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Oh, 
            to be in 
            England<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Now 
            that April's there,<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;And 
            whoever wakes in 
            England<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sees, some morning, 
            unaware,<BR>That the lowest boughs and the brushwood sheaf<BR>Round 
            the elm-tree hole are in tiny leaf,<BR>While the chaffinch sings on
            the orchard 
            bough<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;In 
            England--now!</TT> </P>
            <P><TT>And after April, when May follows,<BR>And the whitethroat 
            builds, and all the swallows!<BR>Hark! where my blossomed pear-tree 
            in the hedge<BR>Leans to the field and scatters on the 
            clover<BR>Blossoms and dewdrops--at the bent spray's 
            edge--<BR>That's the wise thrush; he sings each song twice 
            over,<BR>Lest you should think he never could recapture<BR>The first 
            fine careless rapture!<BR>And though the fields look rough with 
            hoary dew,<BR>All will be gay, when noontide wakes anew<BR>The 
            buttercups, the little children's dower;<BR>--Far brighter than this 
            gaudy melon-flower!<BR>*/<BR></TT></P>
            <P><TT>So it runs; but it is only a momentary memory;<BR>and he 
            knew, when he had done it, and to his</TT> 
    </P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=letter>Indentation des Lettres (courrier) </A></H3>
<P>Laissez les lettres non ident�es, comme vous le feriez pour des <A 
href="#para_space">paragraphes</A>. 
Au lieu d'indenter, ins�rez une ligne vierge avant le d�but de la lettre.</P>
<P>Marquez les ent�tes et les fins de lettres (adresse, date, signature) avec un 
<TT>/*</TT> sur une ligne isol�e avant, et <TT>*/ </TT>sur une ligne isol�e 
apr�s, comme si c'�tait de la po�sie. Mettez une ligne blanche entre ces marques
et le reste du texte. De cette fa�on, ces lignes resteront isol�es au cours de 
la phase d'assemblage � la post-correction. Ne les indentez pas, m�me si, sur 
l'original, elles sont indent�es ou justifi�es � droite. Calez-les sur la marge
de gauche. Le post-correcteur les formatera correctement.</P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Letter Example" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TH vAlign=top width="100%"><IMG height=217 alt="" 
      src="http://www.pgdpcanada.net/c/faq/letter.png" width=500> <BR></TH></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>&lt;i&gt;John James Audubon to Claude Fran�ois 
            Rozier&lt;/i&gt;</TT></P>
            <P><TT>[Letter No. 1, addressed]</TT></P>
            <P><TT>/*<BR>M. FR. ROZIER,<BR>Merchant-Nantes.<BR>NEW YORK, 
            &lt;i&gt;10 January, 1807.&lt;/i&gt;</TT></P>
            <P><TT>DEAR SIR:<BR>*/</TT></P>
            <P><TT>We have had the pleasure of receiving by the 
            &lt;i&gt;Penelope&lt;/i&gt; your<BR>consignment of 20 pieces of 
            linen cloth, for which we send our<BR>thanks. As soon as we have 
            sold them, we shall take great<BR>pleasure in making our 
            return.</TT> </P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=lists>Listes de choses</A></H3>
<P>Marquez ces listes comme de la po�sie, avec une ligne <TT>/* </TT>avant et 
une ligne <TT>*/ </TT>apr�s. Ins�rez une ligne blanche avant /* et une autre 
apr�s */. Ainsi, le post-correcteur saura qu'il doit garder ces lignes s�par�es. 
Marquez ainsi des listes qui ne doivent pas �tre reformat�es, comme des listes 
de questions/r�ponses, des ingr�dients dans une recette, etc. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center summary=List 
border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Texte original:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><PRE>Andersen, Hans Christian   Daguerre, Louis J. M.    Melville, Herman
Bach, Johann Sebastian     Darwin, Charles          Newton, Isaac
Balboa, Vasco Nunez de     Descartes, Ren�          Pasteur, Louis
Bierce, Ambrose            Earhart, Amelia          Poe, Edgar Allan
Carroll, Lewis             Einstein, Albert         Ponce de Leon, Juan
Churchill, Winston         Freud, Sigmund           Pulitzer, Joseph
Columbus, Christopher      Lewis, Sinclair          Shakespeare, William
Curie, Marie               Magellan, Ferdinand      Tesla, Nikola
</PRE></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>/*<BR>Andersen, Hans Christian<BR>Bach, Johann 
            Sebastian<BR>Balboa, Vasco Nunez de<BR>Bierce, Ambrose<BR>Carroll, 
            Lewis<BR>Churchill, Winston<BR>Columbus, Christopher<BR>Curie, 
            Marie<BR>Daguerre, Louis J.M.<BR>Darwin, Charles<BR>Descartes, 
            Ren�<BR>Earhart, Amelia<BR>Einstein, Albert<BR>Freud, 
            Sigmund<BR>Lewis, Sinclair<BR>Magellan, Ferdinand<BR>Melville, 
            Herman<BR>Newton, Isaac<BR>Pasteur, Louis<BR>Poe, Edgar 
            Allan<BR>Ponce de Leon, Juan<BR>Pulitzer, Joseph<BR>Shakespeare, 
            William<BR>Tesla, Nikola<BR>*/ 
  </TT></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=tables>Tableaux</A> </H3>
<P>Marquez les tables de mani�re � ce que le post-correcteur puisse les trouver, 
en les entourant de <TT>/*</TT> et <TT>*/</TT>, comme pour la <A 
href="#poetry">po�sie</A>. 
Mettez une ligne blanche avant /* et une autre apr�s */. Formatez les tables 
avec des espaces de mani�re � ce qu'elles ressemblent approximativement au 
tableau original. Ne faites pas de tableau plus large que 75 caract�res. 
Les r�gles du projet Gutenberg ajoutent: "... sauf si vous ne pouvez pas
faire autrement. Mais JAMAIS plus de 80 caract�res."</P>
<P>Pour aligner les champs, n'utilisez pas de tabulations. Seulement des espaces 
(les tabulations ont des tailles diff�rentes suivant l'�diteur de texte). </P>
<P>Il est souvent difficile de formater des tables en texte ASCII; faites
de votre mieux. Utilisez une fonte monospace, comme <A 
href="http://www.pgdpcanada.net/c/faq/font_sample.php">DPCustomMono</A> ou Courier. Le
but est toujours de pr�server ce que l'auteur a voulu dire, tout en produisant 
un texte �lectronique lisible. Il faudra parfois abandonner le format original 
de la table. Regardez les commentaires de projet, et le forum du projet. 
D'autres correcteurs se sont peut-�tre mis d'accord sur un format sp�cifique. 
Regardez aussi la <A 
href="http:{$forums_url}/viewtopic.php?t=4311">Galerie de tables </A>sur 
le forum. <BR></P>
<P><B>Les notes de bas de page</B> dans les tableaux doivent aller � la fin du 
tableau. Voyez <A 
href="#footnotes">notes de 
bas de page</A> pour plus de details.</P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Table Example 1" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=142 alt="" 
      src="http://www.pgdpcanada.net/c/faq/table1.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><PRE>/*
Deg. C.   Millimeters of Mercury.    Gasolene.
               Pure Benzene.

 -10�               13.4                 43.5
   0�               26.6                 81.0
 +10�               46.6                132.0
  20�               76.3                203.0
  40�              182.0                301.8
*/</PRE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Table Example 2" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=304 alt="" 
      src="http://www.pgdpcanada.net/c/faq/table2.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><PRE>/*
TABLE II.

-----------------------+----+-----++-------------------------+----+------
                       | C  |     ||                         |  C |
Flat strips compared   | o  |     ||                         |  o |
with round wire 30 cm. | p  |Iron.|| Parallel wires 30 cm.   |  p | Iron.
in length.             | p  |     || in length.              |  p |
                       | e  |     ||                         |  e |
                       | r  |     ||                         |  r |
                       | .  |     ||                         |  . |
-----------------------+----+-----++-------------------------+----+------
Wire 1 mm. diameter    | 20 | 100 || Wire 1 mm. diameter     | 20 |  100
-----------------------+----+-----++-------------------------+----+------
        STRIPS.        |    |     ||       SINGLE WIRE.      |    |
0.25 mm. thick, 2 mm.  |    |     ||                         |    |
  wide                 | 15 |  35 || 0.25 mm. diameter       | 16 |   48
Same, 5 mm. wide       | 13 |  20 || Two  similar wires      | 12 |   30
 "   10  "    "        | 11 |  15 || Four    "      "        |  9 |   18
 "   20  "    "        | 10 |  14 || Eight   "      "        |  8 |   10
 "   40  "    "        |  9 |  13 || Sixteen "      "        |  7 |    6
Same strip rolled up in|    |     || Same 16 wires bound     |    |
  the form of a wire   | 17 |  15 ||   close together        | 18 |   12
-----------------------+----+-----++-------------------------+----+------
*/</PRE></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=block_qt>Blocs de citations</A> </H3>
<P>Entourez les blocs de citations par les marques <TT>/# </TT>avant et
<TT>#/</TT> apr�s. Laissez une ligne vide entre des marques et le reste du 
texte. </P>
<P>A part l'ajout de ces marques, les blocs de citations se traitent comme du
texte normal. </P>
<P>Les blocs de citations sont des citations longues (typiquement plusieurs 
lignes, parfois m�me plusieurs pages) incluses dans un livre. Elles sont souvent 
imprim�es en caract�res plus petits avec de plus grandes marges. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Block Quotation" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=475 alt="" 
      src="http://www.pgdpcanada.net/c/faq/bquote.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>
            <P><TT>later day was welcomed in their home on the Hudson.<BR>Dr. 
            Bakewell's contribution was as follows:[24]</TT></P>
            <P><TT>/#<BR>The uncertainty as to the place of Audubon's birth has 
            been<BR>put to rest by the testimony of an eye witness in the 
            person<BR>of old Mandeville Marigny now dead some years. His 
            repeated<BR>statement to me was, that on his plantation at 
            Mandeville,<BR>Louisiana, on Lake Ponchartrain, Audubon's mother 
            was<BR>his guest; and while there gave birth to John James 
            Audubon.<BR>Marigny was present at the time, and from his own lips,
            I have,<BR>as already said, repeatedly heard him assert the above 
            fact.<BR>He was ever proud to bear this testimony of his 
            protection<BR>given to Audubon's mother, and his ability to bear 
            witness as<BR>to the place of Audubon's birth, thus establishing the 
            fact that<BR>he was a Louisianian by birth.<BR>#/<BR></TT></P>
            <P><TT>We do not doubt the candor and sincerity of the<BR>excellent 
            Dr. Bakewell, but are bound to say that the<BR>incidents as related 
            above betray a striking lapse 
  of<BR></TT></P></TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=double_q>Guillemets doubles ( " )</A> </H3>
<P>Si le texte est en anglais, utilisez les guillemets droits ASCII (").</P>
<P>Ne remplacez pas les guillemets par des apostrophes. Laissez ce que l'auteur 
a �crit.</P>
<P>Pour corriger du texte qui n'est pas en anglais, utilisez les caract�res 
appropri�s, s'ils sont disponibles. </P>
<P>En fran�ais, vous pouvez utilisez les guillemets fran�ais 
<FONT color=red>�</FONT><TT>comme ceci</TT><FONT color=red>� </FONT> car ils
sont disponibles dans la liste d�roulante de caract�res. N'oubliez pas d'effacer
les espaces apr�s les guillemets ouvrants et avant les guillemets fermants. Ces espaces
seront rajout�s si n�cessaire en phase de post-correction. Ceci s'applique aussi aux
langues qui utilisent ces guillemets de fa�on invers�e, 
<FONT color=red>�</FONT><TT>comme ceci</TT><FONT color=red>�</FONT>.</P>

<P>Mais les guillemets utilis�s dans certains livres 
allemands&nbsp; <FONT color=red>�</FONT><TT>comme ceci</TT><FONT 
color=red>�</FONT> ne sont pas disponibles dans la liste d�roulante, car ils ne sont
pas Latin-1. Utilisez alors les doubles quotes droites ASCII. 
Comme d'habitude, le chef de projet peut demander de faire autrement, pour un 
livre donn�.</P>
<H3><A name=single_q>Apostrophes ( ' )</A> </H3>
<P>Utilisez l'apostrophe droite ASCII ('). </P>
<P>Ne la changez pas en double quote (guillemets). Laissez ce que l'auteur a 
�crit. </P>
<H3><A name=guill_chaque>Guillemets sur chaque ligne</A> </H3>
<P>Certains livres mettent des guillements au d�but de chaque ligne dans une 
citation; enlevez-les, sauf pour la premi�re ligne. Si la citation continue sur 
plusieurs paragraphes, mettez des guillemets au d�but de chaque paragraphe. </P>
<P>Souvent, les guillemets ne sont pas ferm�s avant la fin de la citation, au 
dernier paragraphe. Ne changez rien. N'ajoutez pas de guillemets fermants qui ne 
seraient pas dans l'original.</P>
<P>Dans certaines langues, il peut y avoir des exceptions. En cas
d'incertitude, consultez les commentaires de projet, ou
le forum de discussion du projet.</P>
<H3><A name=period_s>Points entre les phrases</A> </H3>
<P>Un seul espace apr�s les points et aucun avant. Ne passez pas votre temps � 
supprimer les espaces en trop apr�s le point si les espaces sont d�j� dans le 
texte scann�--il est facile de faire �a automatiquement � la post-correction. 
Voyez l'exemple � la section <A 
href="#chap_head">Ent�tes de 
chapitre</A>.</P>
<H3><A name=punctuat>Caract�res de ponctuation</A> </H3>
<P>En g�n�ral, il n'y a pas d'espace avant&nbsp; les caract�res de ponctuation 
(� part les guillemets ouvrants). Si vous voyez un espace, supprimez-le. 
Cette r�gle s'applique aussi sur des livres en fran�ais, o� des espaces sont 
normalement ins�r�s avant certains signes. </P>
<P>
Vous verrez parfois des espaces "en trop" sur les livres imprim�s aux XVIII�me et XIX�me 
si�cles, car une fraction d'espace est ins�r�e
avant les caract�res "deux points" et "point virgule". Supprimez l'espace dans tous les
cas.</P>
<TABLE cellSpacing=0 cellPadding=4 border=1>
  <COLGROUP>
  <COL width=256>
  <TBODY>
  <TR>
    <TH vAlign=top>Texte scann�</TH>
    <TH vAlign=top>Texte correct</TH></TR>
  <TR>
    <TD vAlign=top><TT>and so it goes ; ever and ever.</TT></TD>
    <TD vAlign=top><TT>and so it goes; ever and ever.</TT> 
</TD></TR></TBODY></TABLE>

<H3><A name=line_br>Retours � la ligne</A> </H3>
<P>Laissez tous les retours � la ligne de mani�re � ce que le correcteur suivant 
puisse comparer les textes facilement. Faites surtout attention aux cas particulier 
des <A href="#eol_hyphen">mots 
coup�s</A>, ou des <A href="#em_dashes"> em-dashes</A>. Si le correcteur qui est pass� avant vous a supprim� les retours � 
la ligne, remettez-les, pour que les lignes correspondent � l'image.</P>
<P>Les lignes vierges qui ne se trouvent pas dans l'image doivent �tre 
supprim�es, sauf celles qui ont �t� ins�r�es intentionnellement pour le 
formatage. Mais des lignes vierges en bas de la page ne posent pas de probl�mes,
ces derni�res pouvant �tre supprim�s facilement � la post-correction. </P>
<H3><A name=extra_sp>Espace additionel entre les mots</A> </H3>
<P>Des espaces suppl�mentaires entre les mots sont une erreur d'OCR courante. Il
n'est pas n�cessaire de supprimer ces espaces �tant donn� qu'il est facile de le 
faire � la post-correction.</P>
<P>Mais les espaces suppl�mentaires autour de la ponctuation, des tirets, etc. 
doivent �tre supprim�s car il est difficile de faire cela automatiquement.</P>
<P>Par exemple, dans <B>A horse&nbsp;;&nbsp;&nbsp;&nbsp;my kingdom for a horse. 
</B>il faut supprimer l'espace avant&nbsp; le point virgule. Mais les deux 
espaces apr�s le point virgule ne posent pas de probl�me : vous n'�tes pas 
oblig�s d'en supprimer un.&nbsp;<BR></P>
<H3>Espaces en fin de ligne</H3>
<P>Inutile d'ins�rer des espaces � la fin des lignes. N'enlevez pas non plus les 
espaces en trop. Tout ceci peut se g�rer automatiquement en phase de 
post-correction.</P>
<H3><A name=line_no>Num�ros de ligne</A> </H3>
<P>Gardez les num�ros de ligne. Placez-les au moins 6 espaces � droite du texte, 
m�me si, sur l'image, ces num�ros sont � gauche. </P>
<P>Il y a souvent des num�ros de lignes dans la marge, sur les livres de po�sie, tous les 5, 
10 ou 20 vers. Nous gardons ces num�ros car ils sont utiles au lecteur.</P>
<H3><A name=extra_s>Espaces suppl�mentaires, ast�risques, lignes entre les 
paragraphes</A></H3>
<P>La plupart des livres commencent un paragraphe imm�diatement apr�s la fin du 
paragraphe pr�c�dent. Mais il peut arriver que deux paragraphes soient s�par�s 
par une ligne horizontale. (une ligne d'�toiles, une ligne de tirets, ou autres 
caract�res, un ligne droite simple ou d�cor�e ou m�me simplement une ligne 
blanche)</P>
<P>Ceci pour exprimer une parenth�se de pens�e, un changement de 
sc�ne, l'�coulement du temps ou pour cr�er un peu de suspense. Ceci refl�te 
l'intention de l'auteur, donc nous pr�servons ces lignes en ins�rant une ligne 
vierge suivie de  <tt>&lt;tb&gt;</tt>, puis une autre ligne
vierge.</P>
<P>Les responsables de projet vous demanderont peut-�tre de
faire la diff�rence entre les diff�rents types de cassure, pour garder
plus d'information.
Par exemple, ils vous demanderont de noter une ligne d'�toiles
par <tt>&lt;tb stars&gt;</tt> et une ligne blanche par
<tt>&lt;tb&gt;</tt>. Suivez attentivement les directives
de projet. Et ne confondez pas les directives des diff�rents
projets!</P>

<P>Parfois, les imprimeurs ajoutent une ligne d�corative en fin de chapitre.
Comme nous marquons d�j� les <A 
href="#chap_head">Ent�tes de 
chapitre</A>, il est inutile d'utiliser la marque "rupture de pens�e". </P>
<P>Dans l'interface de correction, vous pouvez copier/coller la marque de 
"rupture de pens�e". </P><!-- END RR --><BR>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Thought Break" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=264 alt="" 
      src="http://www.pgdpcanada.net/c/faq/tbreak.png" width=500> <BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
    <p><tt>
    like the gentleman with the spiritual hydrophobia<br>
    in the latter end of Uncle Tom's Cabin.<br>
    Unconsciously Mr. Dixon has done his best to<br>
    prove that Legree was not a fictitious character.</tt>
    </p>
    <p><tt>&lt;tb&gt;</tt>
    </p>
    <p><tt>
    Joel Chandler Harris, Harry Stillwell Edwards,<br>
    George W. Cable, Thomas Nelson Page,<br>
    James Lane Allen, and Mark Twain are Southern<br>
    men in Mr. Griffith's class. I recommend</tt>
    </p>
</td></tr>
</TABLE></TD></TR></TBODY></TABLE>

<H3><A name=period_p>Points de suspension "..."</A> </H3>
<P>Les r�gles sont diff�rentes selon que le texte est en anglais ou non.</P>
<P><B>ANGLAIS</B>: Laissez un espace avant les trois points et un espace apr�s. 
L'exception est � la fin d'une phrase : pas d'espace avant, mettre quatre points 
et un espace apr�s. Ou aussi en fin de phrase, apr�s un autre signe de ponctuation, 
mettez trois points, sans espace avant.&nbsp; Par exemple: 
<TT><BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That I know ... is 
true.<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is the 
end....<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Wherefore art thou Romeo?... </TT></P>
<P><I>Parfois vous verrez le signe de ponctuation apr�s les points de 
suspension. </I>Le texte corrig� sera alors comme ceci.&nbsp;</P>
<P><TT>Wherefore art thou Romeo...? </TT></P>
<P>Vous devrez si n�cessaire enlever des points ou en rajouter pour qu'il y en 
ait exactement trois (ou quatre, selon le cas).</P>
<P><B>AUTRES LANGUES</B>: Suivez la r�gle "Respectez le style utilis� sur la 
page imprim�e". Mettez autant de points qu'il y en a d'imprim�s, et ins�rez des
espaces selon ce qui est imprim�. Si, sur une page donn�e, ce n'est pas clair,
ins�rez une note [**pas clair]. Note: les post-processeurs remplaceront d'�ventuels
espaces � l'int�rieur des points de suspension par des espaces ins�cables. 
</P>
<H3><A name=a_chars>Charact�res accentu�s et non-ASCII</A> </H3>
<P>Essayez d'ins�rer les caract�res accentu�s et non-ASCII du jeu Latin-1. Voir
plus loin pour ce qui concerne les signes diacritiques sortant du jeu Latin-1. </P>

<P>Il existe plusieurs fa�ons d'�crire ces caract�res:</P>
<UL compact>
  <LI>Les menus d�roulants de votre interface de correction. 
  <LI>Applications fournies avec votre syst�me d'exploitation. 
  <UL compact>
    <LI>Windows: "Character Map"<BR>Acc�s par:<BR>Start: Run: charmap, 
    or<BR>Start: Accessories: System Tools: Character Map. 
    <LI>Macintosh: Key Caps ou "Keyboard Viewer" 
    <LI>Linux: D�pendant de l'environnement d'IHM. Pour KDE, essayez 
    KCharSelect. </LI></UL>
  <LI>Des programmes en ligne, comme <A 
        href="http://free.pages.at/krauss/computer/xml/daten/edicode.html">Edicode</A>.  
  <LI>Raccourcis clavier.<BR>Voir ci-dessous les tables pour <A 
  href="#a_chars_win">Windows</A> 
  et <A 
  href="#a_chars_mac">Macintosh</A>. 

  <LI>Il est possible de changer les r�glages clavier ou le "locale" pour avoir 
  acc�s directement aux accents.
  <UL compact>
    <LI>Windows: Panneau de contr�le (Keyboard, Input Locales) 
    <LI>Macintosh: Input Menu (sur Menu Bar)
    <LI>Linux: Configuration X. </LI></UL></LI></UL>
<P>Sur le projet Gutenberg, nous avons toujours un texte au format ASCII 7 bits, 
mais nous acceptons aussi des versions avec d'autres encodages, qui pr�servent 
l'information pr�sente dans le texte original. Pour nous, ceci signifie que nous 
utilisons Latin-1, ou ISO 8859-1 et -15, et dans le futur, Unicode. </P><!-- END RR --><A name=a_chars_win></A>
<P><B>Pour Windows</B>: </P>
<UL compact>
  <UL compact>
    <LI>Vous pouvez utiliser la table des caract�res (D�marrer: Ex�cuter: Run: 
    charmap) pour s�lectioner les lettres individuelles et les copier &amp; 
    coller. 
    <LI>Si vous utilisez l'interface de correction avanc�e, le tag <I>more</I> 
    cr�e une fen�tre pop-up contenant ces caract�res, depuis laquelle vous 
    pouvez copier/coller. 
    <LI>Vous pouvez taper les codes ALT+Nombre pour g�n�rer ces caract�res. 
    <BR>(Ils sont bien plus rapide � utiliser que copier &amp; coller une fois 
    que vous y �tes habitu�s). <BR>Pressez la touche Alt et tapez les quatre 
    chiffres dans le pav� num�rique (les chiffres au-dessus des lettres ne 
    fonctionnent pas). <BR>Vous devez taper les 4 chiffres, y compris le premier 
    0. Remarquez que le code de la version majuscule d'une lettre accentu�e est 
    inf�rieur de 32 � sa version minuscule. <BR>(Ceci marche sur un clavier 
    anglais. Pas forc�ment pour d'autres).&nbsp;<BR>La table ci dessous montre 
    les codes que nous utilisons (<A 
    href="http://www.pgdpcanada.net/c/faq/charwin.pdf">Version imprimable de cette 
    table)</A>. <BR>N'utilisez pas d'autres caract�res sp�ciaux � moins que le 
    responsable du projet vous le demande dans les <A 
    href="#comments">Commentaires 
    de projet</A>. </LI></UL><BR>
  <TABLE rules=all align=center summary="Raccourcis Windows" border=6>
    <TBODY>
    <TR>
      <TH bgColor=cornsilk colSpan=14>Raccourcis Windows pour caract�res 
        Latin-1</TH></TR>
    <TR bgColor=cornsilk>
      <TH colSpan=2>` grave</TH>
      <TH colSpan=2>� acute (aigu)</TH>
      <TH colSpan=2>^ circumflex</TH>
      <TH colSpan=2>~ tilde</TH>
      <TH colSpan=2>� umlaut</TH>
      <TH colSpan=2>� ring</TH>
      <TH colSpan=2>� ligature</TH></TR>
    <TR>
      <TD title="Small a grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0224</TD>
      <TD title="Small a acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0225</TD>
      <TD title="Small a circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0226</TD>
      <TD title="Small a tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0227</TD>
      <TD title="Small a umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0228</TD>
      <TD title="Small a ring" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0229</TD>
      <TD title="Small ae ligature" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0230</TD></TR>
    <TR>
      <TD title="Capital A grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0192</TD>
      <TD title="Capital A acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0193</TD>
      <TD title="Capital A circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0194</TD>
      <TD title="Capital A tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0195</TD>
      <TD title="Capital A umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0196</TD>
      <TD title="Capital A ring" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0197</TD>
      <TD title="Capital AE ligature" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0198</TD></TR>
    <TR>
      <TD title="Small e grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0232</TD>
      <TD title="Small e acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0233</TD>
      <TD title="Small e circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0234</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small e umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0235</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital E grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0200</TD>
      <TD title="Capital E acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0201</TD>
      <TD title="Capital E circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0202</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital E umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0203</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Small i grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0236</TD>
      <TD title="Small i acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0237</TD>
      <TD title="Small i circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0238</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small i umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0239</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital I grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0204</TD>
      <TD title="Capital I acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0205</TD>
      <TD title="Capital I circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0206</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital I umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0207</TD>
      <TH bgColor=cornsilk colSpan=2>/ slash</TH>
      <TH bgColor=cornsilk colSpan=2>� ligature</TH></TR>
    <TR>
      <TD title="Small o grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0242</TD>
      <TD title="Small o acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0243</TD>
      <TD title="Small o circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0244</TD>
      <TD title="Small o tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0245</TD>
      <TD title="Small o umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0246</TD>
      <TD title="Small o slash" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0248</TD>
      <TD title="Small oe ligature" align=middle bgColor=mistyrose>� </TD>
      <TD>[oe]</TD></TR>
    <TR>
      <TD title="Capital O grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0210</TD>
      <TD title="Capital O acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0211</TD>
      <TD title="Capital O circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0212</TD>
      <TD title="Capital O tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0213</TD>
      <TD title="Capital O umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0214</TD>
      <TD title="Capital O slash" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0216</TD>
      <TD title="Capital OE ligature" align=middle bgColor=mistyrose>� </TD>
      <TD>[OE]&nbsp;</TD></TR>
    <TR>
      <TD title="Small u grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0249</TD>
      <TD title="Small u acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0250</TD>
      <TD title="Small u circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0251</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small u umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0252</TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD></TR>
    <TR>
      <TD title="Capital U grave" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0217</TD>
      <TD title="Capital U acute" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0218</TD>
      <TD title="Capital U circumflex" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0219</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital U umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0220</TD>
      <TH bgColor=cornsilk colSpan=2>currency </TH>
      <TH bgColor=cornsilk colSpan=2>mathematics </TH></TR>
    <TR>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD title="Small n tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0241</TD>
      <TD title="Small y umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0255</TD>
      <TD title=Cents align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0162</TD>
      <TD title=plus/minus align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0177</TD></TR>
    <TR>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD></TD>
      <TD title="Capital N tilde" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0209</TD>
      <TD title="Capital Y umlaut" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0159</TD>
      <TD title=Pounds align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0163</TD>
      <TD title=Multiplication align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0215</TD></TR>
    <TR>
      <TH bgColor=cornsilk colSpan=2>�edilla </TH>
      <TH bgColor=cornsilk colSpan=2>Icelandic </TH>
      <TH bgColor=cornsilk colSpan=2>marks </TH>
      <TH bgColor=cornsilk colSpan=2>accents </TH>
      <TH bgColor=cornsilk colSpan=2>punctuation </TH>
      <TD title=Yen align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0165</TD>
      <TD title=Division align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0247</TD></TR>
    <TR>
      <TD title="Small c cedilla" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0231</TD>
      <TD title="Capital Thorn" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0222</TD>
      <TD title=Copyright align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0169</TD>
      <TD title="acute accent" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0180</TD>
      <TD title="Inverted Question Mark" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0191</TD>
      <TD title=Dollars align=middle bgColor=mistyrose>$ </TD>
      <TD>Alt-0036</TD>
      <TD title="Logical Not" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0172</TD></TR>
    <TR>
      <TD title="Capital C cedilla" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0199</TD>
      <TD title="Small thorn" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0254</TD>
      <TD title="Registration Mark" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0174</TD>
      <TD title="umlaut accent" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0168</TD>
      <TD title="Inverted Exclamation" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0161</TD>
      <TD title="General Currency" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0164</TD>
      <TD title=Degrees align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0176</TD></TR>
    <TR>
      <TH bgColor=cornsilk colSpan=2>superscripts </TH>
      <TD title="Capital Eth" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0208</TD>
      <TD title=Trademark align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0153</TD>
      <TD title="macron accent" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0175</TD>
      <TD title="guillemot left" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0171</TD>
      <TD></TD>
      <TD></TD>
      <TD title=Micro align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0181</TD></TR>
    <TR>
      <TD title="superscript 1" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0185</TD>
      <TD title="Small eth" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0240</TD>
      <TD title="Paragraph (pilcrow)" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0182</TD>
      <TD title=cedilla align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0184</TD>
      <TD title="guillemot right" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0187</TD>
      <TH bgColor=cornsilk colSpan=2>ordinals </TH>
      <TD title="1/4 Fraction" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0188</TD></TR>
    <TR>
      <TD title="superscript 2" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0178</TD>
      <TH bgColor=cornsilk colSpan=2>sz ligature </TH>
      <TD title=Section align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0167</TD>
      <TD></TD>
      <TD></TD>
      <TD title="Middle dot" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0183</TD>
      <TD title="Masculine Ordinal" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0186</TD>
      <TD title="1/2 Fraction" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0189</TD></TR>
    <TR>
      <TD title="superscript 3" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0179</TD>
      <TD title="sz ligature" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0223</TD>
      <TD title="Broken Vertical bar" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0166</TD>
      <TD></TD>
      <TD></TD>
      <TD title=asterisk align=middle bgColor=mistyrose>* </TD>
      <TD>Alt-0042</TD>
      <TD title="Feminine Ordinal" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0170</TD>
      <TD title="3/4 Fraction" align=middle bgColor=mistyrose>� </TD>
      <TD>Alt-0190</TD></TR></TBODY></TABLE>
  <P>Notez le traitement sp�cial de la ligature oe. Par exemple, le 
  mot c&oelig;ur devient c[oe]ur. </p>
  <P><B>Pour Apple Macintosh</B>: </P>
  <UL compact>
    <LI>Vous pouvez utiliser le Apple Key Caps en tant que r�f�rence. Dans l'OS 
    9 &amp; pr�c�dents, il est localis� dans le Menu Pomme; Dans OS X jusqu'� 
    10.2, il est dans Applications, Utilities .<BR>Ceci affiche l'image d'un 
    clavier, et en pressant MAJ, OPT et command/pomme ou une combinaison de ces 
    touches vous verrez comment produire chaque caract�re. Utilisez cette 
    r�f�rence pour voir comment taper chaque caract�re, ou vous pouvez copier 
    &amp; coller de cette application vers le document. 
    <LI>Dans l'OS X 10.3 et plus, on utilise une palette disponible par le menu 
    Input (le menu d�roulant attach� � l'icone "drapeau" de votre "locale".)
    Elle s'appelle "Show Keyboard Viewer". Si ce n'est pas dans votre menu 
    Input, ou si vous n'avez pas ce menu, activez-la en ouvrant "System 
    Preferences", panneau "International", et choisissez le panneau "Input
    menu". "Show input menu in menu bar" doit �tre coch�e. Dans la vue 
    "spreadsheet", cochez la case pour "Keyboard viewer" pour tous les "locales" 
    d'entr�e que vous utilisez. 
    <LI>Si vous utilisez l'interface de correction avanc�e, le tag <I>more</I> 
    cr�e une fen�tre pop-up contenant ces caract�res, depuis laquelle vous 
    pouvez copier/coller. 
    <LI>Vous pouvez aussi utiliser le raccourci Apple Opt- . <BR>Une fois qu'on 
    a l'habitude des codes, c'est plus rapide que copier/coller. Appuyez sur 
    Opt, tapez le symbole d'accent, puis la lettre � accentuer (pour certains 
    codes, il suffit de maintenir Opt appuy�e, puis taper le symbole). 
    <BR><BR>(Ceci marche sur un clavier anglais. Pas forc�ment pour 
    d'autres).&nbsp;<BR>La table ci dessous montre les codes que nous utilisons 
    (<A href="http://www.pgdpcanada.net/c/faq/charapp.pdf">Version imprimable de cette 
    table)</A>. <BR>N'utilisez pas d'autres caract�res sp�ciaux � moins que le 
    responsable du projet vous le demande dans les <A 
    href="#comments">Commentaires 
    de projet</A>. </LI></UL><BR></UL><BR><A name=a_chars_mac></A>
<TABLE rules=all align=center summary="Raccourcis Mac" border=6>
  <TBODY>
  <TR bgColor=cornsilk>
    <TH colSpan=14>Raccourcis Mac pour caract�res Latin-1</TH>
  <TR bgColor=cornsilk>
    <TH colSpan=2>` grave</TH>
    <TH colSpan=2>� acute (aigu)</TH>
    <TH colSpan=2>^ circumflex</TH>
    <TH colSpan=2>~ tilde</TH>
    <TH colSpan=2>� umlaut</TH>
    <TH colSpan=2>� ring</TH>
    <TH colSpan=2>� ligature</TH></TR>
  <TR>
    <TD title="Small a grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, a</TD>
    <TD title="Small a acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, a</TD>
    <TD title="Small a circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, a</TD>
    <TD title="Small a tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, a</TD>
    <TD title="Small a umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, a</TD>
    <TD title="Small a ring" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-a </TD>
    <TD title="Small ae ligature" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-' </TD></TR>
  <TR>
    <TD title="Capital A grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, A</TD>
    <TD title="Capital A acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, A</TD>
    <TD title="Capital A circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, A</TD>
    <TD title="Capital A tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, A</TD>
    <TD title="Capital A umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, A</TD>
    <TD title="Capital A ring" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-A </TD>
    <TD title="Capital AE ligature" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-" </TD></TR>
  <TR>
    <TD title="Small e grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, e</TD>
    <TD title="Small e acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, e</TD>
    <TD title="Small e circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, e</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small e umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, e</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital E grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, E</TD>
    <TD title="Capital E acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, E</TD>
    <TD title="Capital E circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, E</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital E umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, E</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Small i grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, i</TD>
    <TD title="Small i acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, i</TD>
    <TD title="Small i circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, i</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small i umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, i</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital I grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, I</TD>
    <TD title="Capital I acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, I</TD>
    <TD title="Capital I circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, I</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital I umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, I</TD>
    <TH bgColor=cornsilk colSpan=2>/ slash</TH>
    <TH bgColor=cornsilk colSpan=2>� ligature</TH></TR>
  <TR>
    <TD title="Small o grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, o</TD>
    <TD title="Small o acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, o</TD>
    <TD title="Small o circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, o</TD>
    <TD title="Small o tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, o</TD>
    <TD title="Small o umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, o</TD>
    <TD title="Small o slash" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-o </TD>
    <TD title="Small oe ligature" align=middle bgColor=mistyrose>� </TD>
    <TD>[oe]</TD></TR>
  <TR>
    <TD title="Capital O grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, O</TD>
    <TD title="Capital O acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, O</TD>
    <TD title="Capital I circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, O</TD>
    <TD title="Capital O tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, O</TD>
    <TD title="Capital O umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, O</TD>
    <TD title="Capital O slash" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-O </TD>
    <TD title="Capital OE ligature" align=middle bgColor=mistyrose>� </TD>
    <TD>[OE]</TD></TR>
  <TR>
    <TD title="Small u grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, u</TD>
    <TD title="Small u acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, u</TD>
    <TD title="Small u circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, u</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small u umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, u</TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD></TR>
  <TR>
    <TD title="Capital U grave" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-~, U</TD>
    <TD title="Capital U acute" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-e, U</TD>
    <TD title="Capital U circumflex" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-i, U</TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital U umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, U</TD>
    <TH bgColor=cornsilk colSpan=2>currency </TH>
    <TH bgColor=cornsilk colSpan=2>mathematics </TH></TR>
  <TR>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD title="Small n tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, n</TD>
    <TD title="Small y umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, y</TD>
    <TD title=Cents align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-4 </TD>
    <TD title=plus/minus align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-+ </TD></TR>
  <TR>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD></TD>
    <TD title="Capital N tilde" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-n, N</TD>
    <TD title="Capital Y umlaut" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-u, Y</TD>
    <TD title=Pounds align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-3 </TD>
    <TD title=Multiplication align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-V </TD></TR>
  <TR>
    <TH bgColor=cornsilk colSpan=2>�edilla </TH>
    <TH bgColor=cornsilk colSpan=2>Icelandic </TH>
    <TH bgColor=cornsilk colSpan=2>marks </TH>
    <TH bgColor=cornsilk colSpan=2>accents </TH>
    <TH bgColor=cornsilk colSpan=2>punctuation </TH>
    <TD title=Yen align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-y </TD>
    <TD title=Division align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-/ </TD></TR>
  <TR>
    <TD title="Small c cedilla" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-c </TD>
    <TD title="Capital Thorn" align=middle bgColor=mistyrose>� </TD>
    <TD>Shift-Opt-5</TD>
    <TD title=Copyright align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-g </TD>
    <TD title="acute accent" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-E </TD>
    <TD title="Inverted Question Mark" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-? </TD>
    <TD title=Dollars align=middle bgColor=mistyrose>$ </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title="Logical Not" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-l </TD></TR>
  <TR>
    <TD title="Capital C cedilla" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-C </TD>
    <TD title="Small thorn" align=middle bgColor=mistyrose>� </TD>
    <TD>Shift-Opt-6</TD>
    <TD title="Registration Mark" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-r </TD>
    <TD title="umlaut accent" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-U </TD>
    <TD title="Inverted Exclamation" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-1 </TD>
    <TD title="General Currency" align=middle bgColor=mistyrose>� </TD>
    <TD>Shift-Opt-2</TD>
    <TD title=Degrees align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-* </TD></TR>
  <TR>
    <TH bgColor=cornsilk colSpan=2>superscripts </TH>
    <TD title="Capital Eth" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title=Trademark align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-2 </TD>
    <TD title="macron accent" align=middle bgColor=mistyrose>� </TD>
    <TD>Shift-Opt-,</TD>
    <TD title="guillemot left" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-\ </TD>
    <TD></TD>
    <TD></TD>
    <TD title=Micro align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-m </TD></TR>
  <TR>
    <TD title="superscript 1" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title="Small eth" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title="Paragraph (pilcrow)" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-7 </TD>
    <TD title=cedilla align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-Z </TD>
    <TD title="guillemot right" align=middle bgColor=mistyrose>� </TD>
    <TD>Shift-Opt-\</TD>
    <TH bgColor=cornsilk colSpan=2>ordinals </TH>
    <TD title="1/4 Fraction" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD></TR>
  <TR>
    <TD title="superscript 2" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TH bgColor=cornsilk colSpan=2>sz ligature </TH>
    <TD title=Section align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-6 </TD>
    <TD></TD>
    <TD></TD>
    <TD title="Middle dot" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-8 </TD>
    <TD title="Masculine Ordinal" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-0 </TD>
    <TD title="1/2 Fraction" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD></TR>
  <TR>
    <TD title="superscript 3" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title="sz ligature" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-s </TD>
    <TD title="Broken Vertical bar" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD>
    <TD></TD>
    <TD></TD>
    <TD title=asterisk align=middle bgColor=mistyrose>* </TD>
    <TD>(none)&nbsp;� </TD>
    <TD title="Feminine Ordinal" align=middle bgColor=mistyrose>� </TD>
    <TD>Opt-9 </TD>
    <TD title="3/4 Fraction" align=middle bgColor=mistyrose>� </TD>
    <TD>(none)&nbsp;� </TD></TR></TBODY></TABLE>
<P>�&nbsp;Note: Pas de raccourci clavier. Utilisez les menus. </P>
<P>Notez le traitement sp�cial de la ligature oe. Par exemple, le
  mot c&oelig;ur devient c[oe]ur. </p>
<H3><A name=char_diacr>Caract�res avec marques diacritiques</A></H3>
<P>Sur certains projets, vous trouverez des caract�res avec des signes sp�ciaux 
au-dessus ou au-dessous du caract�re latin normal. Ce sont des <I>marques 
diacritiques</I>. Elles indiquent une prononciation sp�ciale. Nous les indiquons 
dans notre texte corrig� avec un codage sp�cifique, comme <TT>[)x]</TT> pour une 
breve (accent en forme de u) sur un x, ou <TT>[x)]</TT> pour une breve dessous. 
</P>
<P>Mettez bien des crochets (<TT>[&nbsp;]</TT>) autour, pour que le 
post-correcteur sache quel signe s'applique � quelle lettre. Le post-correcteur 
remplacera ces combinaisons de caract�res par le caract�re correct (en Unicode 
par exemple). </P>
<P>N'utilisez pas ce syst�me pour coder les caract�res qui sont pr�sents dans 
Latin-1. Utilisez alors directement ce caract�re (voir <A 
href="#a_chars">ici</A>). <!-- END RR -->
<P>La table ci-dessous liste nos codes. Le "x" repr�sente le caract�re accentu�. 
<BR>Quand vous corrigez un texte, utilisez le VRAI caract�re, pas le x donn� 
dans l'exemple. </P><!--
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
<TABLE rules=all align=center summary=Diacriticals border=6>
  <TBODY>
  <TR bgColor=cornsilk>
    <TH colSpan=4>Symboles avec marques diacritiques</TH>
  <TR bgColor=cornsilk>
    <TH>diacritical mark</TH>
    <TH>sample</TH>
    <TH>above</TH>
    <TH>below</TH></TR>
  <TR>
    <TD>macron (straight line)</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[=x]</TT></TD>
    <TD align=middle><TT>[x=]</TT></TD></TR>
  <TR>
    <TD>2 dots (diaresis, umlaut)</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[:x]</TT></TD>
    <TD align=middle><TT>[x:]</TT></TD></TR>
  <TR>
    <TD>1 dot</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[.x]</TT></TD>
    <TD align=middle><TT>[x.]</TT></TD></TR>
  <TR>
    <TD>grave accent</TD>
    <TD align=middle>`</TD>
    <TD align=middle><TT>[`x]</TT> or <TT>[\x]</TT></TD>
    <TD align=middle><TT>[x`]</TT> or <TT>[x\]</TT></TD></TR>
  <TR>
    <TD>acute accent (aigu)</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>['x]</TT> or <TT>[/x]</TT></TD>
    <TD align=middle><TT>[x']</TT> or <TT>[x/]</TT></TD></TR>
  <TR>
    <TD>circumflex</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[^x]</TT></TD>
    <TD align=middle><TT>[x^]</TT></TD></TR>
  <TR>
    <TD>caron (v-shaped symbol)</TD>
    <TD align=middle><FONT size=-2>&#8744;</FONT></TD>
    <TD align=middle><TT>[vx]</TT></TD>
    <TD align=middle><TT>[xv]</TT></TD></TR>
  <TR>
    <TD>breve (u-shaped symbol)</TD>
    <TD align=middle><FONT size=-2>&#8746;</FONT></TD>
    <TD align=middle><TT>[)x]</TT></TD>
    <TD align=middle><TT>[x)]</TT></TD></TR>
  <TR>
    <TD>tilde</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[~x]</TT></TD>
    <TD align=middle><TT>[x~]</TT></TD></TR>
  <TR>
    <TD>cedilla</TD>
    <TD align=middle>�</TD>
    <TD align=middle><TT>[,x]</TT></TD>
    <TD align=middle><TT>[x,]</TT></TD></TR></TBODY></TABLE>
<H3><A name=f_chars>Alphabets non latins</A> </H3>
<P>Certains textes utilisent des caract�res non latins (autrement dit, autres 
que A..Z). Grecs, H�breux, Cyrilliques (utilis�s pour le russe et autres langues 
slaves), ou Arabes. </P>
<P>Pour le grec, faites une translitt�ration. Autrement dit, traduisez chaque 
caract�re grec en son �quivalent latin. Le grec appara�t tellement souvent dans 
nos textes que nous avons inclus dans les interface un outil de 
translitt�ration. </P>
<P>Appuyez sur le bouton "Greek" en bas de l'�cran pour faire appara�tre 
l'outil. Dans l'outil, cliquez sur les caract�res qui correspondent aux 
caract�res grecs que vous voyez dans le texte original, et un caract�re latin 
appara�tra dans la zone de texte. A la fin, vous pouvez copier-coller le contenu 
de la zone de texte vers votre page de travail. Entourez le texte obtenu par les 
marques <TT>[Greek:&nbsp;</TT> et <TT>]</TT>. Par exemple, <B>&#914;&#953;&#946;&#955;&#959;&#962;</B> devient 
<TT>[Greek: Biblos]</TT>. ("livre", vous �tes bien chez DP!)</P>
<P>Si vous n'�tes pas s�r de votre translitt�ration, ajoutez une �toile, pour 
attirer l'attention du correcteur suivant, ou du post-correcteur.</P>
<P>Les autres langues ne se traitent pas aussi facilement. Ajoutez les marques 
<TT>[Cyrillic:&nbsp;**]</TT>, <TT>[Hebrew:&nbsp;**]</TT>, ou 
<TT>[Arabic:&nbsp;**]</TT>. Et laissez le texte tel qu'il a �t� scann�. Ajoutez 
bien les deux �toiles, pour attirer l'attention du post-correcteur.</P><!-- END RR -->
<UL compact>
  <LI>Grec: <A href="http://gutenberg.net/howto/greek/">Table de conversion Grec 
  vers ASCII</A> (du Project Gutenberg) (ou utilisez l'outil).&nbsp; 
  <LI>Cyrillique: N'essayez de corriger du texte en cyrillique que si 
  vous ma�trisez bien les langues concern�es. Sinon, marquez le texte comme 
  indiqu� ci-dessus. Vous pouvez aussi utiliser
  <A href="http://learningrussian.com/transliteration.htm"> cette table
de translitt�ration</A>.
  <LI>H�breu, Arabe: Non recommand� � moins que vous lisiez ces langues 
  couramment. Il existe des difficult�s importantes dans la conversion de ces 
  langues � l'ASCII et ni <A href="http://texts01.archive.org/dp/">Distributed 
  Proofreaders</A> ni le <A href="http://www.gutenberg.net/">Project 
  Gutenberg</A> n'ont encore choisi de m�thode standard. </LI></UL>
<H3><A name=fract_s>Fractions</A> </H3>
<P>Convertissez les <B>fractions</B> de cette mani�re: <TT>2�</TT> devient 
<TT>2-1/2</TT>. Le tiret emp�che les deux parties d'�tre s�par�es par une retour 
� la ligne au cours du r�assemblage des lignes. </P>
<H3><A name=page_ref>R�f�rences aux pages "cf. p. 123"</A> </H3>
<P>Laissez ces r�f�rences telles qu'elles sont dans le texte, � moins que le 
responsable de projet ne vous dise le contraire dans les <A 
href="#comments">Commentaires 
de Projet</A>. </P>
<H3><A name=bk_index>Index</A> </H3>
<P>Laissez les num�ros de page dans les index. Entourez l'index par deux lignes 
<TT>/*</TT> et <TT>*/</TT>, avec une ligne blanche avant <TT>/*</TT> et une 
autre apr�s <TT>*/</TT>.</P>
<P>N'alignez pas les num�ros les uns sur les autres (comme sur l'image): mettez 
simplement un point-virgule ou "deux points", puis le num�ro de page.&nbsp;</P>
<P>Parfois, les index sont imprim�s sur deux colonnes. L'espace est plus �troit, 
et une entr�e donn�e de l'index peut �tre coup�e en deux lignes. Rejoignez les 
deux parties. Dans des index, avec cette r�gle, il est possible d'avoir une 
ligne tr�s longue. Ce n'est pas grave, le post-correcteur le g�rera. </P>
<P>Mettez une ligne blanche entre chaque entr�e de l'index.</P>
<P>Pour les listes des sous-sujets dans un index, commencez chacun sur une 
nouvelle ligne, et indentez de 2 espaces. </P>
<P>Si un index est en plusieurs sections, (A,B,C...), traitez-les comme des <A 
href="#sect_head">ent�tes de 
section</A>, en les s�parant par deux lignes blanches. 
<P>Les vieux livres imprimaient parfois le premier mot de chaque lettre de 
l'index enti�rement en majuscule; changez ces mots pour que le style corresponde 
� celui utilis� dans le reste de l'index.</P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Rejoining Index Lines" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Texte scann�:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>Elizabeth I, her royal Majesty 
            the<BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Queen, 123, 
            144-155.<BR>&nbsp;&nbsp;birth of, 145.<BR>&nbsp;&nbsp;christening, 
            146-147.<BR>&nbsp;&nbsp;death and burial, 152.<BR><BR>Ethelred II,
            the Unready, 33. </TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�: (avec lignes 
      r�unies)</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>/*<BR>Elizabeth I, her royal Majesty the Queen, 123, 
            144-155.<BR>&nbsp;&nbsp;birth of, 145.<BR>&nbsp;&nbsp;christening, 
            146-147.<BR>&nbsp;&nbsp;death and burial, 152.<BR><BR>Ethelred II, 
            the Unready, 33.<BR>*/</TT> 
</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE><BR>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Aligning Index Subtopics" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Texte scann�:</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD>Hooker, Jos., maj. gen. U. S. V., 345; 
            assigned<BR>&nbsp;&nbsp;to command Porter's corps, 350;
            afterwards,<BR>&nbsp;&nbsp;McDowell's, 367; in pursuit of Lee, 
            380;<BR>&nbsp;&nbsp;at South Mt., 382; unacceptable to 
            Halleck,<BR>&nbsp;&nbsp;retires from active service, 
            390.<BR><BR>Hopkins, Henry H., 209; notorious secessionist 
            in<BR>&nbsp;&nbsp;Kanawha valley, 217; controversy with 
            Gen.<BR>&nbsp;&nbsp;Cox over escaped slave, 233.<BR><BR>Hosea, Lewis 
            M., 187; capt. on Gen. Wilson's staff, 
    194.<BR></TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�: (avec 
      sous-sujets align�s)</TH></TR>
  <TR>
    <TD vAlign=top>
      <TABLE align=left summary="" border=0>
        <TBODY>
        <TR>
          <TD><TT>/*<BR>Hooker, Jos., maj. gen. U.S.V., 
            345;<BR>&nbsp;&nbsp;assigned to command Porter's corps, 
            350;<BR>&nbsp;&nbsp;afterwards, McDowell's, 367;<BR>&nbsp;&nbsp;in 
            pursuit of Lee, 380;<BR>&nbsp;&nbsp;at South Mt., 
            382;<BR>&nbsp;&nbsp;unacceptable to Halleck, retires from active 
            service, 390.<BR><BR>Hopkins, Henry H., 
            209;<BR>&nbsp;&nbsp;notorious secessionist in Kanawha valley, 
            217;<BR>&nbsp;&nbsp;controversy with Gen. Cox over escaped slave, 
            233;<BR><BR>Hosea, Lewis M., 187;<BR>&nbsp;&nbsp;capt. on Gen. 
            Wilson's staff, 194.<BR>*/</TT> 
</TD></TR></TBODY></TABLE></TD></TR></TBODY></TABLE>
<H3><A name=play_n>Th��tre.</A> </H3>
<P>Pour toutes les pi�ces.</P>
<UL compact>
  <LI>Traitez les listes de personnages (Dramatis Person�) comme des <A 
  href="#lists">listes</A>. 
  <LI>Ins�rez quatre lignes blanches avant le d�but d'un acte. 
  <LI>Ins�rez deux lignes blanches avant le d�but d'une sc�ne. 
  <LI>Dans les dialogues, ins�rez une ligne blanche avant chaque prise de 
  parole. 
  <LI>
  <P>Marquez tous les noms des acteurs en italique/gras/capitales selon qu'ils sont en <A 
  href="#italics">Italiques</A> 
  ou <A 
  href="#bold">Gras</A> ou en <A href="#word_caps">Capitales</A> dans 
  le texte original.
  <LI>Les notes de sc�ne (didascalies) seront format�es telles qu'elles sont 
  dans le texte original. <BR>Si la note est sur une ligne isol�e, laissez-la 
  ainsi; si elle est apr�s une ligne de dialogue, laissez-la ainsi; mais si elle est 
sur une ligne de dialogue et cal�e contre
la marge de droite, laissez six espaces entre le dialogue et la didascalie. 
<BR>Parfois, une note de sc�ne commence par un crochet ouvrant, qui n'est jamais referm�. 
  Nous gardons cette convention: ne fermez pas le crochet. Mettez les marques 
  d'italiques, s'il y a lieu, � l'int�rieur des crochets.</LI></UL>
<P>Pour les pi�ces en vers. </P>
<UL compact>
  <LI>Les r�gles de po�sie s'appliquent aux pi�ces en vers. Entourez les vers 
  par des lignes <TT>/*</TT> et <TT>*/</TT>, comme pour de la po�sie.</LI>
  <LI> Mais une didascalie ne doit pas �tre entour�e par <TT>/*</TT> et <TT>*/</TT> </LI>
  <LI>Si un vers est partag� entre deux personnages, la seconde partie du vers 
  sera imprim�e indent�e. Gardez cette identation. </LI>
  <LI>Si un vers est coup� parce qu'il est trop long sur la page imprim�e,
  rejoignez les deux partie du vers sur une m�me ligne (comme pour la po�sie en 
  g�n�ral). <BR>Si la seconde partie d'un vers ne fait qu'un mot, alors elle 
  sera imprim�e au-dessus ou au-dessous de la ligne principale, et pr�c�d�e 
  d'une "(", au lieu d'avoir une ligne pour elle seule.<BR>Voir l' <A 
  href="#play3">exemple</A>. 
  </LI></UL>
<P>Regardez les <A 
href="#comments">Commentaires 
de projet</A>, car le chef de projet peut demander un formatage diff�rent. </P><!-- END RR -->
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Th��tre: exemple 1" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=430 alt="title page image" 
      src="http://www.pgdpcanada.net/c/faq/play1.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
  <TR>
    <TD vAlign=top width="100%">
      <TABLE align=left summary="" border=0>
        <TBODY>
 <tr><td>
<p><tt>/*<br>
Has not his name for nought, he will be trode upon:<br>
What says my Printer now?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Here's your last Proof, Sir.<br>
You shall have perfect Books now in a twinkling.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; These marks are ugly.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; He says, Sir, they're proper:<br>
Blows should have marks, or else they are nothing worth.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; But why a Peel-crow here?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; I told 'em so Sir:<br>
A scare-crow had been better.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; How slave? look you, Sir,<br>
Did not I say, this &lt;i&gt;Whirrit&lt;/i&gt;, and this &lt;i&gt;Bob&lt;/i&gt;,<br>
Should be both &lt;i&gt;Pica Roman&lt;/i&gt;.
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; So said I, Sir, both &lt;i&gt;Picked Romans&lt;/i&gt;,<br>
And he has made 'em &lt;i&gt;Welch&lt;/i&gt; Bills,<br>
Indeed I know not what to make on 'em.
</tt></p><p><tt>
&lt;i&gt;Lap.&lt;/i&gt; Hay-day; a &lt;i&gt;Souse&lt;/i&gt;, &lt;i&gt;Italica&lt;/i&gt;?
</tt></p><p><tt>
&lt;i&gt;Clow.&lt;/i&gt; Yes, that may hold, Sir,<br>
&lt;i&gt;Souse&lt;/i&gt; is a &lt;i&gt;bona roba&lt;/i&gt;, so is &lt;i&gt;Flops&lt;/i&gt; too.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>
<br>

<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Th��tre: exemple 2" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG height=680 alt="title page image" 
      src="http://www.pgdpcanada.net/c/faq/play2.png" width=500><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
   <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
&lt;sc&gt;Clin.&lt;/sc&gt; And do I hold thee, my Antiphila,<br>
Thou only wish and comfort of my soul!<br>
<br>
&lt;sc&gt;Syrus.&lt;/sc&gt; In, in, for you have made our good man wait. (&lt;i&gt;Exeunt.&lt;/i&gt;<br>
*/<br>
<br>
<br>
<br>
<br>
ACT THE THIRD.<br>
<br>
<br>
&lt;sc&gt;Scene I.&lt;/sc&gt;<br>
<br>
<br>
/*<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; 'Tis now just daybreak.--Why delay I then<br>
To call my neighbor forth, and be the first<br>
To tell him of his son's return?--The youth,<br>
I understand, would fain not have it so.<br>
But shall I, when I see this poor old man<br>
Afflict himself so grievously, by silence<br>
Rob him of such an unexpected joy,<br>
When the discov'ry can not hurt the son?<br>
No, I'll not do't; but far as in my pow'r<br>
Assist the father. As my son, I see,<br>
Ministers to th' occasions of his friend,<br>
Associated in counsels, rank, and age,<br>
So we old men should serve each other too.<br>
*/<br>
<br>
<br>
&lt;sc&gt;SCENE II.&lt;/sc&gt;<br>
<br>
&lt;i&gt;Enter&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;<br>
<br>
<br>
/*<br>
&lt;sc&gt;Mene.&lt;/sc&gt; (&lt;i&gt;to himself&lt;/i&gt;). Sure I'm by nature form'd for misery<br>
Beyond the rest of humankind, or else<br>
'Tis a false saying, though a common one,<br>
"That time assuages grief." For ev'ry day<br>
My sorrow for the absence of my son<br>
Grows on my mind: the longer he's away,<br>
The more impatiently I wish to see him,<br>
The more pine after him.<br>
<br>
&lt;sc&gt;Chrem.&lt;/sc&gt; But he's come forth. (&lt;i&gt;Seeing&lt;/i&gt; &lt;sc&gt;Menedemus.&lt;/sc&gt;)<br>
Yonder he stands. I'll go and speak with him.<br>
Good-morrow, neighbor! I have news for you;<br>
Such news as you'll be overjoy'd to hear.<br>
*/</tt></p>
</td></tr>
</tbody></table>
     </td>
    </tr>
  </tbody>
</table>

<BR><A 
name=play3>
<!-- Example --></A>
<TABLE cellSpacing=0 cellPadding=4 width="100%" align=center 
summary="Th��tre: exemple 3" border=1>
  <TBODY>
  <TR>
    <TH align=left bgColor=cornsilk>Exemple d'image:</TH></TR>
  <TR align=left>
    <TD vAlign=top width="100%"><IMG  alt="title page image" 
      src="http://www.pgdpcanada.net/c/faq/play3.png" ><BR></TD></TR>
  <TR>
    <TH align=left bgColor=cornsilk>Texte correctement format�:</TH></TR>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>[&lt;i&gt;Hernda has come from the grove and moves up to his side&lt;/i&gt;]<br>
<br>
/*<br>
&lt;i&gt;Her.&lt;/i&gt; [&lt;i&gt;Adoringly&lt;/i&gt;] And you the master!<br>
<br>
&lt;i&gt;Hud.&lt;/i&gt; Daughter, you owe my lord Megario<br>
Some pretty thanks.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[&lt;i&gt;Kisses her cheek&lt;/i&gt;]<br>
<br>
&lt;i&gt;Her.&lt;/i&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I give them, sir.<br>
*/</tt></p>
</td></tr>
</TBODY></TABLE></TD></TR></TBODY></TABLE>
<br>
<a name="play4"><!-- Example --></a>
<table width="100%" align="center" border="1" cellpadding="4"
 cellspacing="0" summary="Th��tre, exemple 4">
  <tbody>
    <tr>
      <th align="left" bgcolor="cornsilk">Exemple d'image</th>
    </tr>
    <tr align="left">
      <td width="100%" valign="top"><img src="http://www.pgdpcanada.net/c/faq/play4.png" width="502"
          height="98" alt="Plays image"><br>
      </td>
    </tr>
    <tr>
      <th align="left" bgcolor="cornsilk">Texte correctement format�:</th>
    </tr>
    <tr>
      <td width="100%" valign="top">
<table summary="" border="0" align="left"><tr><td>
<p><tt>/*<br>
Am. Sure you are fasting;<br>
Or not slept well to night; some dream (Ismena?)<br>
<br>
Ism. My dreams are like my thoughts, honest and innocent,<br>
Yours are unhappy; who are these that coast us?<br>
You told me the walk was private.<br>
*/</tt></p>
</td></tr></table>
      </td>
    </tr>
  </tbody>
</table>


<H3><A name=anything>Tout ce qui n�cessite �galement un traitement sp�cial, ou
dont vous n'�tes pas s�r.</A> </H3>
<P>Si vous rencontrez quelque chose qui n'est pas couvert par ces directives et 
qui vous para�t avoir besoin d'un traitement sp�cial, ou que vous n'�tes pas s�r 
de quelque chose, posez votre question sur le forum du projet (en pr�cisant le 
num�ro de la page qui pose probl�me), et ajoutez une note dans le texte
� l'endroit qui poste probl�me . Cette note signalera le probl�me � la personne
qui passera cette page ensuite (correcteur, formateur ou post-processeur).</P>
<P>Mettez un crochet ouvrant puis deux �toiles avant le d�but de la note,
et un crochet fermant apr�s
 <TT>[**</TT> et <TT>]</TT> pour bien s�parer votre note du texte de 
l'auteur (n'oubliez pas les deux �toiles).  Ceci 
signale au post-correcteur qu'il doit s'arr�ter et examiner ce texte et l'image 
correspondante et r�soudre le probl�me. Si vous voyez une note laiss�e
par le volontaire qui est pass� avant vous, laissez-la. Si vous n'�tes
pas d'accord avec lui, rajoutez votre propre note. </P>
<!-- END RR -->

<h3><a name="prev_notes">Notes et commentaires des correcteurs pr�c�dents.</a></h3>
<p>Les notes des correcteurs pr�c�dents <b>doivent </b> �tre gard�es. 
Vous pouvez ajouter que vous �tes d'accord ou pas d'accord,
mais m�me si vous �tes s�r de la solution, ne supprimez
pas la note. Si vous avez une source qui permet
de donner la r�ponse au probl�me, citez cette source, pour que
le post-processeur s'y r�f�re lui aussi.
</p>
<P>Si vous  r�solvez un probl�me pos� par un correcteur qui a laiss� une note,
vous pouvez �crire un message � ce correcteur (en cliquant sur son 
nom dans l'interface de correction), pour lui expliquer comment g�rer la 
situation la prochaine fois. Mais ne supprimez jamais sa note.</P>
<!-- END RR -->
<TABLE cellSpacing=0 width="100%" summary="Autres r�gles" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver>&nbsp;</TD></TR></TBODY></TABLE><BR>
<H2><A name=sp_ency></A><A name=sp_chem></A><A name=sp_math></A><A 
name=sp_poet></A>R�gles sp�cifiques pour livres sp�ciaux</H2>
<P>Les types de livres suivants ont des directives sp�cifiques, qui s'ajoutent 
aux pr�sentes directives ou les remplacent. Ils sont souvent plus difficiles � 
corriger, et donc recommand�s aux correcteurs exp�riment�s ou aux experts dans 
le domaine.&nbsp;</P>
<P>Cliquez sur un lien pour voir les directives sur un de ces types de 
livres.&nbsp;</P>
<UL compact>
  <LI><B><A href="http://www.pgdpcanada.net/c/faq/doc-ency.php">Encyclop�dies</A></B> 
  <LI><B><A href="http://www.pgdpcanada.net/c/faq/doc-poet.php">Po�sie</A></B> 
  <LI><B>Chimie [� compl�ter]</B> 
  <LI><B>Math�matiques [� compl�ter]</B> </LI></UL><BR>
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><BR></TD></TR></TBODY></TABLE><BR>
<H2>Probl�mes courants</H2>
<H3><A name=OCR_1lI>Probl�mes d'OCR 1-l-I </A></H3>
<P>Les logiciels d'OCR (Reconnaissance Optique de Caract�res) ont souvent des 
difficult�s pour faire la diff�rence entre le nombre un ( 1 ), la lettre 
minuscule L ( l ) et la lettre majuscule i ( I ). C'est particuli�rement vrai 
pour certains vieux livres dont les pages sont en mauvais �tat. Faites attention 
� ces derniers. Lisez le contexte de la phrase pour d�terminer quel est le 
caract�re correct, et soyez attentifs--souvent votre cerveau corrige 
automatiquement ces erreurs pendant que vous lisez.<BR></P>L'utilisation d'une 
fonte comme <A href="http://www.pgdpcanada.net/c/faq/font_sample.php">DPCustomMono</A> 
permet de rep�rer facilement ce type de probl�me. 
<H3><A name=OCR_0O>Probl�mes d'OCR 0-O</A></H3>
<P>Les logiciels d'OCR ont souvent des difficult�s pour faire la diff�rence 
entre le chiffre 0 et le O majuscule. C'est particuli�rement vrai pour certains 
vieux livres dont les pages sont en mauvais �tat. Faites attention � ces
derniers. Lisez le contexte de la phrase pour d�terminer quel est le caract�re 
correct, et soyez attentifs--souvent votre cerveau corrige automatiquement ces 
erreurs pendant que vous lisez.<BR></P>L'utilisation d'une fonte comme <A 
href="http://www.pgdpcanada.net/c/faq/font_sample.php">DPCustomMono</A> permet de 
rep�rer facilement ce type de probl�me. 
<H3><A name=OCR_other>Probl�mes d'OCR: tirets </A></H3>
<P>Les logiciels d'OCR ont fr�quemment des probl�mes avec les tirets et les 
traits d'union.Le texte pass� � l'OCR a souvent un seul tiret pour un trait qui 
devrait en avoir 2. Voir les r�gles sur les <A 
href="#em-dashes">traits et 
tirets</A>.</P>Ici aussi, l'utilisation d'une fonte comme <A 
href="http://www.pgdpcanada.net/c/faq/font_sample.php">DPCustomMono</A> permet de 
rep�rer facilement ce type de probl�me. 
<H3><A name=OCR_scanno>Probl�mes d'OCR: Scannos</A></H3>
<P>Un autre probl�me courant, avec les OCRs, est celui de la mauvaise 
reconnaissance de certains caract�res: les "scannos" (comme "typos"). Le 
r�sultat peut �tre un mot qui: 
<UL compact>
  <LI>a l'air correct � premi�re vue, mais qui est mal �crit. Vous le verrez facilement en 
  faisant tourner le v�rificateur d'orthographe. 
  <LI>a �t� transform� en autre mot, valide, mais qui n'est pas celui qu'a �crit 
  l'auteur. Ces erreurs ne peuvent pas �tre rep�r�es automatiquement, mais 
  seulement par quelqu'un qui lit vraiment le texte. </LI></UL>
<P>En anglais, l'exemple le plus courant de scanno du second type est "arid" pour 
"and". En fran�ais, 
"m�me" pour "m�me", "ros�" pour "rose", "a" pour "�", "vint" pour "v�nt", etc. 
Nous les appelons les "Scannos 
furtifs", car ils sont plus difficiles � voir. Des exemples ont �t� collect�s <A 
href="{$forums_url}/viewtopic.php?t=1563">ici</A>. Les scannos sont
plus faciles � voir avec une fonte monospace comme <A 
href="http://www.pgdpcanada.net/c/faq/font_sample.php">DPCustomMono</A> ou Courier. 
</P><!-- END RR -->
<H3><A name=hand_notes>Notes manuscrites dans le livre</A> </H3>
<P>N'incluez pas les notes manuscrites dans le livre (� moins que quelqu'un ait 
repass� des lettres mal imprim�es ou effac�es). N'incluez pas les notes �crites 
en marge par les lecteurs.<BR></P>
<H3><A name=bad_image>Mauvaises images</A> </H3>
<P>Si une image est mauvaise (refuse de se charger, est coup�e au milieu, 
illisible), postez un message � propos de cette image dans le <A 
href="#forums">forum</A> du 
projet. Ne cliquez pas sur �Return page to round�; si vous le faites, la 
personne suivante obtiendra cette page. � la place, cliquez sur "Report bad 
page" pour mettre la page � part.&nbsp;</P>
<P>Parfois, les images sont tr�s grosses, et votre navigateur aura des probl�mes 
pour les afficher, surtout si vous avez beaucoup de fen�tres ouvertes ou si 
votre ordinateur est vieux. Avant de d�clarer la page "mauvaise", cliquez sur la 
ligne "image" en bas de la page pour faire appara�tre l'image sur une fen�tre � 
part. Si l'image est alors bonne, alors le probl�me vient probablement de votre 
syst�me, ou de votre navigateur.&nbsp;</P>
<P>Il est relativement courant que l'image soit bonne, mais que le texte pass� � 
l'OCR ne contienne pas la premi�re (et deuxi�me) ligne. Retapez alors les lignes 
qui manquent. Si presque toutes les lignes manquent, alors soit tapez toute la
page (si vous voulez le faire), ou cliquez sur le bouton �Return page to round� 
et la page sera rendue � quelqu'un d'autre. Si plusieurs pages sont comme �a, 
postez un message sur le <A
href="#forums">forum</A> 
pour l'indiquer au responsable du projet.</P>
<H3><A name=bad_text>Image ne correspondant pas au texte</A> </H3>
<P>Si l'image ne correspond pas au texte, postez un message � ce propos sur le 
<A href="#forums">forum</A>. 
Ne cliquez pas sur �Return page to round�; si vous le faites, la personne 
suivante obtiendra cette page. � la place, cliquez sur "Report bad page" pour 
mettre la page � part.&nbsp;</P>
<H3><A name=round1>Erreurs des correcteurs pr�c�dents</A> </H3>
<P>Si le correcteur pr�c�dent � fait beaucoup d'erreurs ou a laiss� passer un 
grand nombre de choses, vous pouvez lui envoyer un message en cliquant sur son 
nom. �a vous permettra de lui envoyer un message priv� par le forum.<BR><I>Soyez 
aimable!</I> Ces gens sont des volontaires, essayant d'habitude de faire de leur 
mieux. Le but du message est de les informer de la mani�re correcte de corriger, 
plut�t que de les critiquer. Donnez-leur un exemple pr�cis de ce qu'ils ont 
fait, et de ce qu'ils auraient d� faire. <BR>Si le correcteur pr�c�dent a fait 
un travail remarquable, vous pouvez �galement lui envoyer un message pour le lui 
dire, sourtout s'il a travaill� sur une page tr�s difficile.<BR></P>
<H3><A name=p_errors>Erreurs d'impression/d'orthographe</A> </H3>
<P>Corrigez toujours les fautes de scan. Mais ne corrigez pas une faute 
d'orthographe ou d'impression. Parfois, certains mots ne s'�crivaient pas comme 
aujourd'hui quand le livre a �t� imprim�. Gardez l'ancienne orthographe, en particulier
en ce qui concerne les accents.  </P>
<P>Si vous avez vraiment un doute, alors mettez une note dans le txte 
<TT>[**typo for texte?]</TT> et demandez dans le forum du projet. Si vous 
changez vraiment quelque chose, alors mettez une note d�crivant ce que
vous avez chang� <TT>[**Transcriber's Note:
typo fixed, changed from "txte" to "texte"]</TT>. N'oubliez pas les deux �toiles <TT>**</TT> 
pour que le post-correcteur voie le probl�me. </P>
<H3><A name=f_errors>Erreurs factuelles dans le texte</A> </H3>
<P>En g�n�ral, ne corrigez pas les erreurs sur les faits dans les livres. 
Beaucoup de livres que nous corrigeons d�crivent des choses que nous savons �tre 
fausses comme �tant des faits. Laissez-les tel que l'auteur les a �crits. </P>
<P>Une exception possible est dans les livres techniques ou scientifiques, dans 
lesquel un formule connue ou une �quation peuvent �tre indiqu�es incorrectement. 
(En particulier si elles sont not�es d'une mani�re correcte sur d'autres pages 
du livre). Parlez-en au responsable de projet&nbsp; soit en envoyant un message 
via le <A 
href="#forums">Forum</A>, ou 
en ins�rant <TT>[sic** expliquez-votre-souci]</TT> � cet endroit du texte. </P>
<H3><A name=uncertain>Points incertains</A> </H3>
<P>[...� compl�ter...] </P>
<H2 align=center>Fin des directives</H2>
<TABLE cellSpacing=0 width="100%" border=0>
  <TBODY>
  <TR>
    <TD bgColor=silver><BR></TD></TR></TBODY></TABLE>Retournez � la <A
href="http://www.pgdpcanada.net/">Page principale de Distributed
Proofreaders</A><BR><BR><BR>

<?
theme('','footer')
?>
