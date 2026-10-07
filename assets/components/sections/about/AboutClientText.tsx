import GraduateIcon from "../../ui/GraduateIcon";

export default function AboutClientText() {
    return (
        <div className="text-wrapper">
            <p>
                Je suis Sylvain, développeur web à Toulouse. Je crée des sites
                depuis 2017, et j'ai passé cinq ans chez Ludilabel, une boutique
                en ligne d'étiquettes personnalisées, avant de me lancer en
                freelance.
            </p>
            <p>
                Avec moi, vous avez un seul interlocuteur du début à la fin. On
                commence par parler de votre activité et de ce que vous attendez
                du site. Je vous propose une maquette, je développe, puis je
                vous montre comment modifier vous-même vos textes et vos photos.
                Après la mise en ligne, je reste joignable : certains clients
                travaillent avec moi depuis 2020.
            </p>
            <p>
                J'explique les choses simplement. Et si une solution plus simple
                suffit pour votre besoin, je vous le dis.
            </p>
            <p>
                En dehors du développement, je grimpe depuis six ans, j'ai joué
                au foot pendant seize ans, et je vais beaucoup au cinéma.
            </p>

            <hr />
            <div className="diplomas">
                <GraduateIcon />
                <ul>
                    <li>
                        <div className="infos">
                            <span className="date">2023</span>
                            <span className="title">
                                Titre RNCP niveau 6 (Bac+3/4), 3W Academy
                                (Toulouse)
                            </span>
                        </div>
                        <p>Concepteur Développeur d'Applications </p>
                    </li>
                    <li>
                        <div className="infos">
                            <span className="date">2019</span>
                            <span className="title">
                                Titre RNCP niveau 5 (Bac+2), Adrar (Toulouse)
                            </span>
                        </div>
                        <p>Développeur Web et Web Mobile </p>
                    </li>
                    <li>
                        <div className="infos">
                            <span className="date">2017</span>
                            <span className="title">
                                Master 1 (Bac+4), Rennes 2
                            </span>
                        </div>
                        <p>Numérique et Média Interactifs </p>
                    </li>
                </ul>
            </div>
        </div>
    );
}
