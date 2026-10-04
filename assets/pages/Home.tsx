import About from "../components/sections/About";
import Footer from "../components/sections/Footer";
import Hero from "../components/sections/Hero";
import Parcours from "../components/sections/Parcours";
import Projects from "../components/sections/Projects";
import Services from "../components/sections/Services";
import Contact from "../components/sections/Contact";
import { ProjectProps } from "../types/projects";
import { ExperienceProps } from "../types/experiences";
import { OfferProps } from "../types/offers";

type HomeProps = {
    projects: ProjectProps[];
    offers: OfferProps[];
    experiences: ExperienceProps[];
};

// les id servent d'ancres pour la nav et de racine aux styles (#home, #a-propos...)
export default function Home({ projects, offers, experiences }: HomeProps) {
    return (
        <>
            <section id="home">
                <Hero />
            </section>
            <section id="services">
                <Services offers={offers} />
            </section>
            <section id="projects">
                <Projects projects={projects} />
            </section>
            <section id="a-propos">
                <About />
                <section id="parcours">
                    <Parcours experiences={experiences} />
                </section>
            </section>
            <section id="contact">
                <Contact />
            </section>
            <Footer />
        </>
    );
}
