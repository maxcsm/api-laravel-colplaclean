
<!DOCTYPE html>
<html lang="fr">
<head>
    <style>
      table, th, td {
      padding: 2px;
      border: 2px solid black;
      border-collapse: collapse;
      }

      .cell-highlight {
      border: 4px solid green;
}
p{ font-size: 10px;}
    </style>
  </head>

<table style="width: 100%; border-collapse: collapse;" border="1">
<tbody>
<tr>
<td style="width: 25%;"><img src="{{ public_path('/images/logo.png') }}" style="width: 200px;"></td>
<td style="width: 50.5613%;text-align:center;">
<div class="page" title="Page 1">
<div class="section">
<div class="layoutArea">
<div class="column">
<h5><strong>PRESTATIONS D'ENTRETIEN ANNUEL/TRAITEMENT UNIQUE/DÉCAFARDISATION/DÉRATISATION
            DÉRATISATION/HYGIÈNE DÉBACTÉRISATION
            THERMONÉBULISATION/LUTTE ANTI PIGEONS/AUTRE
</strong></h5>
</div>
</div>
</div>
</div>
</td>

</tr>
</tbody>
</table>


<table style="width: 100.00%;border: solid; height: 40px;">
<tbody><tr >
<td style="width: 100%;background-color:#BBBBBB;text-align:center;"> <p><b>CONTRAT</b></p></td></tr>
</tbody>
</table>


<table style="width: 100.00%;border: solid;">
<tbody>

<tr >
<td style="width: 20%; height: 10px; text-align: left;">

<p>Date : <b>{{$docdate}}</b> <br> 

@foreach($users1 as $item)
<p>Client/Raison sociale : <b> {{$item->company}}</b> <br> 
Addresse de facturation : <b>  {{$item->shipping_address}} {{$item->shipping_cp}} {{$item->shipping_city}}</b> <br>
<b>  {{$item->shipping_state}} {{$item->shipping_country}}</b> <br>
Téléphone  : <b>   {{$item->shipping_phone}} </b> <br>
Téléphone autre  : <b>  {{$item->billing_phone}} </b> <br>
TVA : <b> {{$item->tva_number}} </b> <br>
Siret: <b>  {{$item->siret_number}} </b> <br>
<br> 
Personne à contacter et/ou ayant procuration pour valider les prestations:<br> 
Nom Prénom : <b>{{$item->salutation}} {{$item->firstname}} {{$item->lastname}} </b> <br> 
<b> {{$item->address}} {{$item->cp}} {{$item->city}}</b> <br>
<b>  {{$item->state}} {{$item->country}}</b> <br>
Mobile : <b> {{$item->phone_mobile}} </b> <br>
Téléphone :  <b>{{$item->phone_number}} </b> <br>
E-mail :  <b>  {{$item->email}} </b> <br>
@endforeach

</td>
</tr>
</tbody>
</table>




<table style="width: 100.00%;border: solid; height: 40px;">
<tbody><tr >
<td style="width: 100%;"> 

<p>
A. Prestations :
</p>
<p>
1. <b>{{$label2}}</b> de désinfection dans le cadre du bâtiment précipités contre <b>{{$label1}}</b> 
</p>
<p>
2. Zones de traitement :<b> {{$label3}} </b>
</p>
<p>
B. Garantie :
Dont le cadre d’un traitement unique d'une période non reconductible de <b> {{$label4}} </b> mois après la dernière application, ou en cas de réinfection entre deux visites une intervention 
supplémentaire gratuite peut être effectuée sur simple appel du client, sauf contre les insectes volants saisonniers garantie de mars à octobre.</p>

<p>

C. Conditions de vente : Le client s'engage à payer un montant forfaitaire de<b> {{$label5}} </b>  € htva à la souscription du contrat et ensuite tous les <b> {{$label6}} </b> mois. À l'anniversaire de la souscrip- tion

par le client de la prestation d'entretien, les prix seront adaptés automatiquement en fonction de l'évolution des charges salariales du secteur, sauf réglementation contraire
du Ministère des Affaires Economiques.</p>

<p>

2. a. Le contrat est conclu pour une durée minimale de <b> {{$label7}} </b> an(s). Après cette durée minimale, le contrat sera tacitement reconduit d'année en année, sauf notification par l'une ou
l'autre des parties d'un renon par lettre recommandée avec accusé de réception au plus tard trois mois avant l'expiration de la première période minimale de <b> {{$label8}} </b> an(s) ou par la
suite, avant l'expiration des échéances suivantes. Le contrat prend cours à la date de la souscription, l'anniversaire de cette date constituant le terme de la période minimale
ou des échéances annuelles suivantes. </p>


<p>
b. En cas de résiliation anticipée du contrat par le client, celui-ci sera redevable d'une indemnité forfaitaire et irréductible égale à 100% des montants encore
conventionnellement dus, jusqu'à la prochaine échéance régulière suivant les termes de l'article 2 a), et sans que la SPRL. Coplateck soit  encore tenue de quelconque
prestation.</p>

<p>

C En cas de refus d'application, le client ne jouira d'aucune remise de prix, celui-ci étant fixé forfaitairement.</p>

<p>

d. Après la reconduction du contrat à l'issue de la période minimale, la S.P.R.L. Coplateck appréciera souverainement le nombre de prestations et d'applications de
désinfection requises. Cette modification du nombre de prestations ou d'applications n'affectera ni la garantie offerte par la S.P.RL. Coplateck en vertu du contrat, ni le
montant forfaitaire dû.</p>

<p>

Les factures sont payables dès <b> {{$label9}} </b> jours réception, anticipativement. À défaut de paiement de la facture à son échéance, elle sera majorée sans mise en demeure préalable et dès la date d'échéance,
d'un montant forfaitaire et irréductible de 15 % avec un minimum de 50 EUR, à titre d'indemnité conventionnelle. En outre, les factures impayées 4 l'échéance porteront
un intérêt conventionnel fixé à 1% par mois.</p>

<p>

4. En cas de non-paiement d'une facture à son échéance et dès que la S.P.R_L. Coplateck se voit obligée de faire appel à un tiers pour en obtenir le paiement, tous les frais de
recouvrement qui y sont liés pourront être récupérés à charge du client. De même. en cas de non paiement d'une facture à son échéance, la S.P RL. Coplateck se réserve le
droit de suspendre l'exécution de ses propres obligations et d'arrêter ses prestations. Ces prestations ne seront reprises qu'après paiement intégral des factures non payées,
indemnités et intérêts conventionnels et les frais de justice, et ceci sous réserve du droit de la S.P.R.L. Coplateck de considérer la convention résiliée aux torts et griefs du
client.</p>

<p>

5. La S.PR.L. Coplateck ne couvre que les dégâts matériels éventuellement causés par ses services. Ces dégâts doivent lui être signalés par lettre recommandée dans les 48
heures qui suivent les faits, sous peine de nullité. Au cas où la S P.RL. Coplateck n'aurait pas été mise en mesure d'opposer son propre constat des dégâts à la plainte du
client, celle-ci sera considérée comme nulle de droit. En tout état de cause, la responsabilité de la S.P.RL. Coplateck ne saurait excéder les montants payés par le client en
vertu du contrat. Si les prestations de la SPRL Coplateck impliquent l'enlèvement de biens mobiliers, il reviendra au client d'indiquer dès le début de l'opération à la SP.R.L. Coplateck les biens qui ne doivent pas être enlevés. À défaut d'instruction précise de la part du client, la S.P.R.L. Coplateck ne saurait être tenue responsable en cas de perte ou dommage aux biens du client qui auront été enlevés.</p>

<p>

6. En cas de contestation, seuls les tribunaux de Bruxelles sont compétents.
 © condition générale de vente sous copyright. protégé par le droit d'auteur coplateck sprl &
Simont Braun lawyers</p>

</td></tr>
</tbody>
</table>


<table style="width: 100.00%;border: solid;padding-top:100px;">
<tbody>
<tr style="height: 27px;text-align:center;">
<td style="width: 50%; height: 27px;font-size:10px;"><p><b> Signature pour la S.P.R.L COPLATECK </b></td>
<td style="width: 50%; height: 27px;font-size:10px;"><p><b>Signature pour le client<b></p></td>
</tr>

<tr >
<td style="width: 20%; height: 10px; text-align: left;">
<p>Sous réserve d'approbation par la Direction avant exécution - paraphe:
@foreach($users1 as $item)
<p>Nom Prénom : <b> {{$item->firstname}} {{$item->lastname}} </b> </p>
@endforeach
<p>Date : {{$docdate}}
<img src="{{$img2}}" alt="Red dot" />

</td>


<td style="width: 20%; height: 10px; text-align: left;">

<p>Le soussigné, ci-dessus dénommé le client, déciare
explicilement qu'il conclut ce contrat dans le cadre de ses
activités prolessionnelles.</p>
@foreach($users2 as $item)
<p>Nom Prénom : <b> {{$item->firstname}} {{$item->lastname}} </b> </p>
@endforeach
<p>Date : {{$docdate}} </p>
<img src="{{$img1}}" alt="Red dot" />
</td>
</tr>
</tbody>
</table>


<table style="width: 100.00%;border: solid; height: 40px;">
<tbody><tr >
<td style="width: 100%;background-color:#BBBBBB;text-align:center;"> <p><b>SPRL COPLATECK | Rue Des Alliés 302 1190 BRUXELLES
TVA BE0449393377
Tél. : +32 2 5232189 </b></p></td></tr>





</tbody>
</table>




































