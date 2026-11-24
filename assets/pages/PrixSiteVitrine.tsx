import { Link } from "@inertiajs/react";
import Footer from "../components/sections/Footer";
import Contact from "../components/sections/contact/Contact";
import { plans } from "../lib/pricing";

const CLIENT_PATH = "/creation-site-web";

// page « prix d'un site vitrine » : contenu statique, mêmes tarifs que la
// section Tarifs (lib/pricing.ts) ; mise en page de la page locale (_local.scss)
export default function PrixSiteVitrine({ year }: { year: number }) {
    return (
        <>
            <div className="section-container local-container">
                <h1 className="section-title">
                    Prix d’un site vitrine en {year}
                </h1>
                {/* la réponse d'abord : c'est ce que cherche la personne */}
                <p className="local-intro">
                    Un site vitrine coûte chez moi à partir de 1 200 € pour un
                    site essentiel, et à partir de 2 200 € pour un site sur
                    mesure. Le prix final dépend du nombre de pages, du design
                    et des fonctions dont vous avez besoin. Le devis est gratuit
                    et sans engagement.
                </p>

                <section>
                    <h2>Mes tarifs</h2>
                    <ul className="local-cards">
                        {plans.map((plan, index) => (
                            <li key={plan.title} className="local-card">
                                <div className="local-card-title">
                                    <span
                                        className="local-card-number"
                                        aria-hidden="true"
                                    >
                                        {String(index + 1).padStart(2, "0")}
                                    </span>
                                    <h3>{plan.title}</h3>
                                </div>
                                <div className="local-card-body">
                                    <p className="local-card-text">
                                        {plan.description}
                                    </p>
                                    <p className="local-card-footer">
                                        <strong>{plan.price}</strong>
                                    </p>
                                </div>
                            </li>
                        ))}
                    </ul>
                    <p>
                        Ces prix sont indicatifs : chaque projet fait l’objet
                        d’un devis détaillé.
                    </p>
                </section>

                <section>
                    <h2>Ce qui fait varier le prix</h2>
                    <ul className="local-list">
                        <li>
                            <strong>Le nombre de pages</strong> : une page
                            d’accueil, une page de services et un contact ne
                            demandent pas le même travail qu’un site de quinze
                            pages.
                        </li>
                        <li>
                            <strong>Le design</strong> : une mise en page simple
                            et efficace, ou un design créé entièrement pour
                            votre activité.
                        </li>
                        <li>
                            <strong>Les contenus</strong> : textes et photos
                            fournis par vous, ou à reprendre et organiser
                            ensemble.
                        </li>
                        <li>
                            <strong>Les fonctions</strong> : formulaire de
                            contact, prise de rendez-vous, galerie, plusieurs
                            langues… chaque fonction en plus ajoute du
                            développement.
                        </li>
                        <li>
                            <strong>L’espace d’administration</strong> : simple
                            pour modifier vos textes et vos photos, ou complet
                            pour gérer tous vos contenus.
                        </li>
                    </ul>
                </section>

                <section>
                    <h2>Ce qui est compris</h2>
                    <p>
                        Un site 100 % sur mesure, rapide et adapté au mobile,
                        avec un espace d’administration pour le modifier
                        vous-même, le référencement technique (vitesse, balises,
                        données structurées) et la mise en ligne. Vous avez un
                        seul interlocuteur, du premier échange à la mise en
                        ligne.
                    </p>
                </section>

                <section>
                    <h2>Les frais après la mise en ligne</h2>
                    <p>
                        Un site a quelques frais réguliers : le nom de domaine
                        (une dizaine d’euros par an en général) et
                        l’hébergement. Je propose un forfait mensuel
                        d’hébergement et de maintenance qui comprend les mises à
                        jour, la sécurité et les petites modifications. Vous
                        restez libre de gérer l’hébergement vous-même.
                    </p>
                </section>

                <section>
                    <h2>
                        Pourquoi pas un constructeur de site ou une agence ?
                    </h2>
                    <p>
                        Les constructeurs (Wix, Squarespace…) coûtent peu au
                        départ, mais vous payez un abonnement tant que le site
                        existe, et vous faites tout vous-même. Une agence prend
                        en général plus cher, pour un travail réparti entre
                        plusieurs personnes. En freelance, vous payez le site
                        une fois, il vous appartient, et vous parlez directement
                        à la personne qui le construit.
                    </p>
                </section>

                <section className="local-faq">
                    <h2>Questions fréquentes</h2>
                    <h3>Le devis est-il payant ?</h3>
                    <p>
                        Non, le devis est gratuit et sans engagement. Décrivez
                        votre projet avec le formulaire plus bas.
                    </p>
                    <h3>Pourrai-je modifier mon site moi-même ?</h3>
                    <p>
                        Oui, chaque site a un espace d’administration pour
                        changer vos textes, vos photos et vos offres sans passer
                        par moi.
                    </p>
                    <h3>
                        J’ai déjà un site, fait avec l’IA ou un constructeur
                    </h3>
                    <p>
                        Je peux le reprendre ou le refaire selon son état. La
                        refonte est sur devis : le détail des prestations est
                        sur la page{" "}
                        <Link href={`${CLIENT_PATH}#services`}>
                            création de site web
                        </Link>
                        .
                    </p>
                </section>

                <section className="local-cta">
                    <p>
                        Vous voulez connaître le prix de votre site ? Le devis
                        est gratuit et sans engagement.
                    </p>
                    <div className="local-actions">
                        <a href="#contact" className="local-button">
                            Demander un devis
                        </a>
                        <Link
                            href={`${CLIENT_PATH}#projects`}
                            className="local-button secondary"
                        >
                            Voir les réalisations
                        </Link>
                    </div>
                </section>

                <section className="local-author">
                    <p>
                        <strong>Sylvain Pillet</strong>
                        <br />
                        Développeur web freelance à Toulouse, sites vitrines et
                        refontes pour indépendants et TPE, partout en France.
                    </p>
                </section>
            </div>
            {/* id : racine des styles du formulaire (contact.scss), comme sur les accueils */}
            <section id="contact">
                <Contact audience="client" />
            </section>
            <Footer />
        </>
    );
}
