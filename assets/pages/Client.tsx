import About from "../components/sections/About";
import Footer from "../components/sections/Footer";
import Hero from "../components/sections/Hero";
import Parcours from "../components/sections/Parcours";
import Projects from "../components/sections/Projects";
import Services from "../components/sections/Services";
import Contact from "../components/sections/Contact";
import Pricing from "../components/sections/Pricing";
import Process from "../components/sections/Process";
import { ProjectProps } from "../types/projects";
import { ExperienceProps } from "../types/experiences";
import { OfferProps } from "../types/offers";

type ClientProps = {
    projects: ProjectProps[];
    offers: OfferProps[];
    experiences: ExperienceProps[];
};

// les id servent d'ancres pour la nav et de racine aux styles (#home, #a-propos...)
export default function Client({ projects, offers, experiences }: ClientProps) {
    return (
        <>
            <section id="home">
                <Hero
                    audience="client"
                    text={"Création de sites web \n à Toulouse "}
                    intro={
                        <>
                            Développeur web freelance à Toulouse, je crée votre
                            site vitrine avec son espace d’administration, ou je
                            reprends et améliore votre site existant. <br />5
                            ans d’expérience en e-commerce : des sites rapides
                            et bien référencés.
                        </>
                    }

                    details={
                        <>
                            Je vous accompagne de l’idée à la mise en ligne : on
                            échange d’abord sur votre besoin, je construis une
                            première version utilisable, puis j’avance avec vos
                            retours. Vous pouvez ensuite modifier vos contenus
                            vous-même grâce à un espace d’administration simple,
                            et je m’occupe de l’hébergement.
                        </>
                    }
                    cta="Discutons de votre projet"
                />
            </section>
            <section id="services">
                <Services offers={offers} />
            </section>
            <section id="projects">
                <Projects projects={projects} audience="client" />
            </section>
            <section id="a-propos">
                <About />
                <section id="parcours">
                    <Parcours experiences={experiences} audience="client" />
                </section>
            </section>
            <section id="tarifs">
                <Pricing />
            </section>
            <section id="deroule">
                <Process />
            </section>
            <section id="contact">
                <Contact audience="client" />
            </section>
            <Footer />
        </>
    );
}
