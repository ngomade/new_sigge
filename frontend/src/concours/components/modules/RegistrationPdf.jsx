import React from 'react';
import { Document, Page, Text, View, StyleSheet } from '@react-pdf/renderer';

const styles = StyleSheet.create({
  page: {
    padding: 30,
    fontFamily: 'Helvetica',
    display:'flex',
    flexDirection:'column',
    gap:5
  },
  section: {
    marginBottom: 10,
    display:'flex',
  },
  heading: {
    fontSize: 18,
    marginBottom: 10,
  },
  label: {
    fontSize: 12,
    fontWeight: 'bold',
    marginBottom: 2,
  },
  value: {
    fontSize: 12,
    marginBottom: 5,
  },
});

const RegistrationPdf = ({ formData }) => (
  <Document>
    <Page style={styles.page}>
      <View style={styles.section}>
        <Text style={styles.heading}>Informations Personnelles</Text>
        <Text style={styles.label}>Nom: <Text style={styles.value}>{formData.ca_nom}</Text></Text>
        <Text style={styles.label}>Prénom: <Text style={styles.value}>{formData.ca_prenom}</Text></Text>
        <Text style={styles.label}>Date de naissance: <Text style={styles.value}>{formData.ca_date_naiss}</Text></Text>
        <Text style={styles.label}>Lieu de naissance: <Text style={styles.value}>{formData.ca_lieu_naiss}</Text></Text>
        <Text style={styles.label}>Téléphone: <Text style={styles.value}>{formData.ca_telephone}</Text></Text>
        <Text style={styles.label}>Numéro de CNI: <Text style={styles.value}>{formData.ca_num_cni}</Text></Text>
        <Text style={styles.label}>Adresse: <Text style={styles.value}>{formData.ca_adresse}</Text></Text>
        <Text style={styles.label}>Date de délivrance CNI: <Text style={styles.value}>{formData.ca_deliv_cni}</Text></Text>
        <Text style={styles.label}>Résidence: <Text style={styles.value}>{formData.ca_resid}</Text></Text>
        <Text style={styles.label}>Email: <Text style={styles.value}>{formData.ca_email}</Text></Text>
        <Text style={styles.label}>Sexe: <Text style={styles.value}>{formData.ca_sexe}</Text></Text>
        <Text style={styles.label}>Statut matrimonial: <Text style={styles.value}>{formData.ca_statut_mat}</Text></Text>
        <Text style={styles.label}>Première langue: <Text style={styles.value}>{formData.ca_premiere_lang}</Text></Text>
        <Text style={styles.label}>Nationalité: <Text style={styles.value}>{formData.ca_nationalite}</Text></Text>
        <Text style={styles.label}>Région d'origine: <Text style={styles.value}>{formData.ca_region_origine}</Text></Text>
        <Text style={styles.label}>Département d'origine: <Text style={styles.value}>{formData.ca_depart_origine}</Text></Text>
        <Text style={styles.label}>Handicap: <Text style={styles.value}>{formData.ca_handicap}</Text></Text>
      </View>
      <View style={styles.section}>
        <Text style={styles.heading}>Informations Académiques</Text>
        <Text style={styles.label}>Établissement du diplôme: <Text style={styles.value}>{formData.ca_etab_diplome}</Text></Text>
        <Text style={styles.label}>Pays du diplôme: <Text style={styles.value}>{formData.ca_pays_diplome}</Text></Text>
        <Text style={styles.label}>Filière: <Text style={styles.value}>{formData.filiere_code}</Text></Text>
        <Text style={styles.label}>Diplôme d'admission: <Text style={styles.value}>{formData.ca_diplome_admission}</Text></Text>
        <Text style={styles.label}>Série du diplôme: <Text style={styles.value}>{formData.ca_serie_diplome}</Text></Text>
        <Text style={styles.label}>Année du diplôme: <Text style={styles.value}>{formData.ca_annee_diplome}</Text></Text>
        <Text style={styles.label}>Mention du diplôme: <Text style={styles.value}>{formData.ca_mention_diplome}</Text></Text>
        <Text style={styles.label}>Centre d'examen: <Text style={styles.value}>{formData.ca_centre_examen}</Text></Text>
        <Text style={styles.label}>Centre de dépôt: <Text style={styles.value}>{formData.ca_centre_depot}</Text></Text>
        <Text style={styles.label}>Code du site: <Text style={styles.value}>{formData.code_site}</Text></Text>
      </View>
      <View style={styles.section}>
        <Text style={styles.heading}>Informations Parentales</Text>
        <Text style={styles.label}>Nom du père: <Text style={styles.value}>{formData.ca_nom_pere}</Text></Text>
        <Text style={styles.label}>Téléphone du père: <Text style={styles.value}>{formData.ca_telephone_pere}</Text></Text>
        <Text style={styles.label}>Email du père: <Text style={styles.value}>{formData.ca_email_pere}</Text></Text>
        <Text style={styles.label}>Nom de la mère: <Text style={styles.value}>{formData.ca_nom_mere}</Text></Text>
        <Text style={styles.label}>Téléphone de la mère: <Text style={styles.value}>{formData.ca_telephone_mere}</Text></Text>
      </View>
      <View style={styles.section}>
        <Text style={styles.heading}>Documents Nécessaires</Text>
        <Text style={styles.label}>Reçu: <Text style={styles.value}>{formData.ca_recu}</Text></Text>
        <Text style={styles.label}>Numéro du reçu: <Text style={styles.value}>{formData.ca_num_recu}</Text></Text>
      </View>
    </Page>
  </Document>
);

export default RegistrationPdf;
