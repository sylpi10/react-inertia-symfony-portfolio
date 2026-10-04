// import arrow from "../../static/images/arrow.webp";
import me from "../../static/images/me.webp";

export default function About() {
    const getAge = (birthDate: string): number => {
        const today: Date = new Date();
        const birthDateObj: Date = new Date(birthDate);
        let age: number = today.getFullYear() - birthDateObj.getFullYear();
        const month: number = today.getMonth();
        const day: number = today.getDate();

        // Adjust age if birthday hasn't occurred yet this year
        if (
            month < birthDateObj.getMonth() ||
            (month === birthDateObj.getMonth() && day < birthDateObj.getDate())
        ) {
            age--;
        }

        return age;
    };

    const age: number = getAge("1990-03-17");

    return (
        <>
            {/* data-nosnippet : Google ne reprend pas ce texte dans l'extrait de résultat */}
            <div className="section-container about-container" data-nosnippet>
                <div className="content">
                    <h2 className={"section-title"}>En quelques mots</h2>
                    <div className="about-me-wrapper">
                        <div className="name">
                            <h3 className={"person-title"}>
                                Sylvain, {age} ans
                            </h3>
                            {/*<img
                                src={arrow}
                                className="arrow"
                                alt="image de flèche"
                                width="220"
                                height="142"
                            />*/}
                        </div>
                        <div className="image-wrapper">
                            <img
                                src={me}
                                className="profile"
                                alt="Sylvain Pillet, développeur à Toulouse"
                                width="400"
                                height="487"
                                loading="lazy"
                            />
                        </div>
                        <div className="tags">
                            <ul>
                                <li>Développement Front-end</li>
                                <li>Développement Fullstack</li>
                                <li>Mise en production et hébergement</li>
                                <li>SEO / performances</li>
                            </ul>
                        </div>
                    </div>
                    <div className="text-container">
                        <span className="info age" data-move="left">
                            {age} ans
                        </span>
                        <span className="info dev" data-move="bottom">
                            Développeur
                        </span>
                        <div className="text-wrapper">
                            <p>
                                J'ai débuté par l'intégration web en <b>2017</b>{" "}
                                avant de me former aux autres technologies web
                                par moi-même pour ensuite de rejoindre en{" "}
                                <b>2019</b> une formation en développement à{" "}
                                <b>Toulouse</b>.
                            </p>
                            <p>
                                J'ai ensuite exploré différents langages et
                                frameworks, avant de revenir aux fondamentaux du
                                web et à PHP avec une alternance de{" "}
                                <b>Concepteur Développeur d'Applications </b>{" "}
                                chez <b>Ludilabel</b> autour de <b>Symfony</b>,{" "}
                                <b>React</b> et de l'e-commerce avec{" "}
                                <b>Magento</b>. J'y ai pris en charge le
                                frontend et l'<b>UX/UI</b> de la refonte du
                                site, puis développé le Labelmaker, l'outil de
                                personnalisation d'étiquettes intégré à la
                                boutique, un projet qui m'a permis de travailler
                                à la fois le <b>backend</b> et le{" "}
                                <b>frontend</b> avec <b>Symfony</b>
                                et <b>React</b>.
                            </p>
                            <p>
                                Aujourd'hui, je suis développeur web freelance à
                                Toulouse. J'accompagne des indépendants et des
                                petites entreprises sur leurs sites et
                                applications : sites vitrines avec back-office,
                                refontes techniques, applications{" "}
                                <b>Symfony et React ou Next.js </b>, et
                                optimisation des performances et du
                                référencement.
                            </p>
                            <p>
                                Ce que j'aime dans le développement fullstack,
                                c'est donner vie à des idées abstraites :
                                concevoir les modèles de données, puis leur
                                donner forme à travers chaque couche de
                                l'application, jusqu'à l'interface finale.
                            </p>

                            <p>
                                Sinon je suis aussi passionné de <b>Cinéma</b>,
                                ou de sport, j'ai pratiqué le <b>foot</b>{" "}
                                pendant 16 ans et je pratique maintenant{" "}
                                <b>l'escalade</b> depuis 6 ans.
                                <br />
                            </p>
                            <p>
                                Actuellement basé à <b>Toulouse</b>.
                            </p>
                        </div>
                        <span className="info where" data-move="top">
                            Toulouse{" "}
                        </span>
                        <span className="info grimpe" data-move="right">
                            Grimpeur
                        </span>
                    </div>
                </div>
            </div>
            <div className="round"></div>
        </>
    );
}
