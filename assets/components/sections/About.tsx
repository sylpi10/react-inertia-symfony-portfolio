import useGetAge from "../../hooks/useGetAge";
import me from "../../static/images/me.webp";
import { Audience } from "../../types/audience";
import AboutClientSteps from "./about/AboutClientSteps";
import AboutClientText from "./about/AboutClientText";
import AboutTeamSteps from "./about/AboutTeamSteps";
import AboutTeamText from "./about/AboutTeamText";

export default function About({ audience }: { audience: Audience }) {
    const age: number = useGetAge("1990-03-17");

    return (
        <>
            {/* data-nosnippet : Google ne reprend pas ce texte dans l'extrait de résultat */}
            <div className="section-container about-container" data-nosnippet>
                <div className="content">
                    <h2 className={"section-title"}>En quelques mots</h2>
                    <div className="about-me-wrapper">
                        <div className="name">
                            <h3 className={"person-title"}>Sylvain</h3>
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
                        {audience === "team" ? (
                            <AboutTeamSteps />
                        ) : (
                            <AboutClientSteps />
                        )}
                    </div>
                    <div className="text-container">
                        <span className="info age" data-move="left">
                            {age} ans
                        </span>
                        <span className="info dev" data-move="bottom">
                            Développeur
                        </span>

                        {audience === "team" ? (
                            <AboutTeamText />
                        ) : (
                            <AboutClientText />
                        )}

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
