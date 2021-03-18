# PHP vizsgafeladat
 MVC forum
 **Az alkalmazasban nem a megjelenes a lenyeg, formazni nem kell (max annyi hogy ne legyen minden odaomlesztve). JS nem szukseges, MVC strukturaban kell megvalositani a tanult tervezesi mintakkal egy forumot az alabbi szempontok szerint**

* TWIG hasznalata nem kotelezo, de amennyiben nem twiget hasznalunk, valasszuk szet elesen a kliens, es a szerver oldalt, a ketto kozott REST muveletekkel kommunikaljunk

1. Az oldalon csak 1 admin van (o barmit megtehet)
2. Vannak userek akik regisztralhatnak
3. Minden user forumbejegyzeseket irhatnak kategoriakba
4. A kategoriakat csak az admin hozhatja letre.
5. A user ha egy posztot felvett, akkor 5 perce van hogy mnodosithassa vagy torolhesse azt, ezt kovetoen nem modosithatja vagy torolheti a posztjat (csak az admin)
6. A user a sajat profil oldalat elerheti, es modosithatja azt, de csak a sajatjat lathatja.
7. Az admin mindenkit, es mindenkinek a profiljat latja (es barmit megtehet a userekkel)
8. Minden adat validalva van, es minden adat tisztiva.
9. Minden mas itt fel nem sorolt opcio az mindenkinek a sajat belatasara van bizva
