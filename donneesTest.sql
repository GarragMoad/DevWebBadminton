INSERT INTO capitaine (id, joueur_id, nom, prenom, telephone, mail) VALUES
(1, NULL, 'Dupont', 'Jean', '0601020304', 'jean.dupont@example.com'),
(2, NULL, 'Martin', 'Sophie', '0605060708', 'sophie.martin@example.com'),
(3, NULL, 'Durand', 'Paul', '0608091011', 'paul.durand@example.com');


INSERT INTO club (id, nom, sigle, gymnase, string) VALUES
(1, 'Badminton Club Paris', 'BCP', 'Gymnase Jaurès', 'string1'),
(2, 'Lyon Badminton', 'LYB', 'Gymnase Lumière', 'string2'),
(3, 'Marseille Smash', 'MSM', 'Gymnase Prado', 'string3');


INSERT INTO equipe (id, nom_equipe, numero_equipe, score, cpph, capitaine_id) VALUES
(1, 'Les Aiglons', 'A1', 1200.50, 45.60, 1),
(2, 'Les Foudres', 'F1', 980.75, 38.40, 2),
(3, 'Les Phénix', 'P1', 1100.30, 42.20, 3);

INSERT INTO joueur (id, nom, prenom, numero_licence, classement_simple, cpph_simple, classement_mixtes, cpph_mixtes, classement_double, cpph_double) VALUES
(1, 'Dupont', 'Jean', 'LIC101', 'A', 25.50, 'B', 22.30, 'C', 28.40),
(2, 'Martin', 'Sophie', 'LIC102', 'B', 18.70, 'C', 20.20, 'B', 21.10),
(3, 'Durand', 'Paul', 'LIC103', 'C', 30.10, 'A', 32.50, 'A', 35.20);

INSERT INTO joueurs_equipes (joueur_id, equipe_id) VALUES
(1, 1),
(2, 2),
(3, 3),
(1, 2),  -- Jean joue aussi dans l'équipe 2
(3, 1);  -- Paul joue aussi dans l'équipe 1

INSERT INTO type_reception (id, type) VALUES
(1, 'Entraînement'),
(2, 'Compétition');

INSERT INTO reception (id, type_reception_id, club_id, jour, horaire_debut, horaire_fin) VALUES
(1, 1, 1, 'Lundi', '18:00:00', '20:00:00'),
(2, 2, 2, 'Mercredi', '19:00:00', '21:00:00'),
(3, 1, 3, 'Vendredi', '20:00:00', '22:00:00');




