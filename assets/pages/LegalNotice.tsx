// TODO à compléter avant la mise enligne (obligatoire pour un professionnel, loi LCEN art. 6)
const publisher = {
    status: "Entrepreneur individuel",
    siret: "87864762700032",
    address: "rue Henri de Sahuque, 31400, Toulouse",
    phone: "0688103175",
    email: "syl.pillet@hotmail.fr",
};

export default function LegalNotice() {
    return (
        <div className="section-container legal-container">
            <h1 className="section-title">Mentions légales</h1>

            <section>
                <h2>Éditeur du site</h2>
                <p>
                    Sylvain Pillet, {publisher.status}
                    <br />
                    SIRET : {publisher.siret}
                    <br />
                    {publisher.address}
                    <br />
                    Email :{" "}
                    <a href={`mailto:${publisher.email}`}>{publisher.email}</a>
                    <br />
                    Téléphone : {publisher.phone}
                </p>
                <p>Directeur de la publication : Sylvain Pillet.</p>
            </section>

            <section>
                <h2>Hébergement</h2>
                <p>
                    o2switch, Chemin des Pardiaux, 63000 Clermont-Ferrand,
                    France
                    <br />
                    Téléphone : 04 44 44 60 40 –{" "}
                    <a
                        href="https://www.o2switch.fr"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        www.o2switch.fr
                    </a>
                </p>
            </section>

            <section>
                <h2>Propriété intellectuelle</h2>
                <p>
                    Les textes, visuels et le code de ce site sont la propriété
                    de Sylvain Pillet, sauf mention contraire. Toute
                    reproduction sans autorisation est interdite.
                </p>
                <p>
                    Les captures des projets présentés restent la propriété de
                    leurs titulaires respectifs et sont reproduites à titre de
                    référence.
                </p>
            </section>

            <section>
                <h2>Données personnelles</h2>
                <p>
                    Le formulaire de contact collecte votre nom, votre adresse
                    email et votre message, dans le seul but de vous répondre.
                    Ces données ne sont transmises à aucun tiers. Elles sont
                    hébergées en France chez o2switch et conservées au plus 3
                    ans après notre dernier échange.
                </p>
                <p>
                    Le formulaire d’avis collecte le nom et le texte que vous
                    choisissez de publier, avec votre accord explicite. L’avis
                    n’est publié qu’après relecture et reste en ligne tant que
                    vous n’en demandez pas le retrait, à l’adresse ci-dessous.
                </p>
                <p>
                    Conformément au RGPD, vous disposez d’un droit d’accès, de
                    rectification, d’effacement, d’opposition et de limitation
                    du traitement de vos données. Pour l’exercer, écrivez à{" "}
                    <a href={`mailto:${publisher.email}`}>{publisher.email}</a>.
                    Vous pouvez aussi adresser une réclamation à la{" "}
                    <a
                        href="https://www.cnil.fr"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        CNIL
                    </a>
                    .
                </p>
            </section>

            <section>
                <h2>Cookies</h2>
                <p>
                    Ce site n’utilise aucun cookie de mesure d’audience ni
                    publicitaire. Un cookie technique de session peut être
                    déposé à l’envoi du formulaire de contact, pour afficher le
                    message de confirmation ; il ne nécessite pas votre
                    consentement.
                </p>
            </section>
        </div>
    );
}
