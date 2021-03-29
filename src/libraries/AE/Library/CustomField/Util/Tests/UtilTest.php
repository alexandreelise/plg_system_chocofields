<?php
declare(strict_types=1);

/**
 * UtilTest
 *
 * @version       0.1.0
 * @package       UtilTest
 * @author        Alexandre ELISÉ <contact@alexandre-elise.fr>
 * @copyright (c) 2009-2021 . Alexandre ELISÉ . Tous droits réservés.
 * @license       GPL-2.0-and-later GNU General Public License v2.0 or later
 * @link          https://coderparlerpartager.fr
 */

namespace AE\Library\CustomField\Util\Tests;

use AE\Library\CustomField\Core\Constant;
use \AE\Library\CustomField\Util\Util;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use function dirname;
use function file_get_contents;
use const DIRECTORY_SEPARATOR;


/**
 * @package     AE\Library\CustomField\Helper\Tests
 *
 * @since       version
 */
class UtilTest extends TestCase
{
	
	/**
	 * @covers \AE\Library\CustomField\Util\Util
	 * @since  version
	 */
	public function testFlattenAssocArrayReturnsValidData()
	{
		
		$data = [
			'id'                              => 13219,
			'legalStatus'                     =>
				[
					'id'      => 932,
					'labelFr' => 'Service public - CPAS',
					'labelNl' => 'Openbare dienst - OCMW',
				],
			'isInterpreting'                  => false,
			'address'                         =>
				[
					'id'               => null,
					'x'                => 152302,
					'y'                => 171263,
					'lon'              => 4.401445106123757,
					'lat'              => 50.851756126658735,
					'municipalityFr'   => 'SCHAERBEEK',
					'municipalityNl'   => 'SCHAARBEEK',
					'streetFr'         => 'Boulevard Auguste Reyers',
					'streetNl'         => 'Auguste Reyerslaan',
					'number'           => '70',
					'numberBuilding'   => null,
					'zipCode'          => 1030,
					'postalBox'        => null,
					'postalCodeFr'     => '1030 SCHAERBEEK',
					'postalCodeNl'     => '1030 SCHAARBEEK',
					'districtFr'       => 'REYERS',
					'districtNl'       => 'REYERS',
					'districtCode'     => '87',
					'statisticsCode'   => '21015A77-',
					'remarkFr'         => null,
					'remarkNl'         => null,
					'statisticsNameFr' => 'R.T.B.',
					'statisticsNameNl' => 'B.R.T.',
				],
			'categories'                      => null,
			'categoriesObject'                =>
				[
				],
			'sectorsObjects'                  => null,
			'nameOfficialFr'                  => 'COORDINATION DE L\'ACTION SOCIALE DE SCHAERBEEK',
			'nameOfficialNl'                  => 'COÖRDINATIE VAN DE SCHAARBEEKSE SOCIALE ACTIE',
			'descriptionFr'                   => null,
			'descriptionNl'                   => null,
			'activitiesFr'                    => 'Coordination qui regroupe des associations et institutions qui exercent une activité à caractère social ou socio-culturel sur le territoire de la commune de Schaerbeek, afin de soutenir la réflexion commune et l\'action concertée dans la lutte contre la pauvreté et l\'exclusion sociale :
- Rencontres de connaissance du réseau : échange entre travailleurs sociaux d’un quartier ou sur base d’une thématique
- Groupes de travail thématiques autour de projets : Guide (création et suivi du Guide de l\'accompagnant social schaerbeekois), personnes âgées
- Module de présentation du réseau social schaerbeekois
- Journées thématiques, campagnes de sensibilisation, etc.
- Outils de communication : site internet, newsletter, guide/répertoire des services et associations',
			'activitiesNl'                    => 'Coördinatie van verenigingen en instellingen die een sociale of socioculturele activiteit verrichten op het grondgebied van de gemeente Schaarbeek, die nadenkt over de bestrijding van armoede en sociale uitsluiting en actie voert tegen armoede en sociale uitsluiting:
- ontmoetingsmomenten netwerk: uitwisseling tussen sociaal werkers van een wijk of over een thematiek
- thematische werkgroepen rond projecten: Gids (opstellen en opvolgen van de gids voor de sociale begeleiding van de Schaarbekenaars), Ouderen
- presentatiemodule van het sociale netwerk in Schaarbeek
- themadagen, bewustmakingscampagnes, enz.
- communicatietools: website, nieuwsbrief, gids/lijst van de diensten en verenigingen',
			'accessFr'                        =>
				[
					0 => 'Par e-mail',
					1 => 'Par téléphone',
				],
			'accessNl'                        =>
				[
					0 => 'Per e-mail',
					1 => 'Telefonisch',
				],
			'permanencyFr'                    => 'Du lundi au vendredi de 8h30 à 16h',
			'permanencyNl'                    => 'Van maandag tot vrijdag van 8.30 uur tot 16 uur',
			'publicFr'                        => 'Toute organisation schaerbeekoise qui se sent concernée par la lutte contre la pauvreté et l\'exclusion sociale',
			'publicNl'                        => 'Schaarbeekse verenigingen die zich aangesproken voelen door de bestrijding van de armoede en de sociale uitsluiting',
			'publicationFr'                   =>
				[
				],
			'publicationNl'                   =>
				[
				],
			'remarkFr'                        => '- Partenariat entre le CPAS de Schaerbeek et la coordination sociale associative active à Schaerbeek, la Coordination Sociale de Schaerbeek (CSS)',
			'remarkNl'                        => '- Samenwerking tussen het OCMW van Schaarbeek en de sociale coördinatie van verenigingen actief in Schaarbeek, de Sociale Coördinatie van Schaarbeek',
			'pmRp'                            =>
				[
					0 => '0212.347.945',
				],
			'langStatus'                      => 'FR-NL',
			'lastUpdate'                      => '28/08/20',
			'startDate'                       => null,
			'type'                            => null,
			'nameAlternativeFr'               =>
				[
					0 => 'CASS',
				],
			'nameAlternativeNl'               =>
				[
					0 => 'CSSA',
				],
			'nameFormerFr'                    =>
				[
				],
			'nameFormerNl'                    =>
				[
				],
			'nameServiceFr'                   =>
				[
				],
			'nameServiceNl'                   =>
				[
				],
			'telFr'                           =>
				[
					0 => '02/435.51.38',
					1 => '02/435.51.39',
				],
			'telNl'                           =>
				[
					0 => '02/435.51.38',
					1 => '02/435.51.39',
				],
			'emailFr'                         =>
				[
					0 => 'cass@cpas-schaerbeek.be',
				],
			'emailNl'                         =>
				[
					0 => 'cass@cpas-schaerbeek.be',
				],
			'faxFr'                           =>
				[
				],
			'faxNl'                           =>
				[
				],
			'websiteOfficialFr'               =>
				[
					0 => 'http://www.cass-cssa.be',
				],
			'websiteOfficialNl'               =>
				[
					0 => 'http://www.cass-cssa.be',
				],
			'websiteUnofficialFr'             =>
				[
				],
			'websiteUnofficialNl'             =>
				[
				],
			'websiteBelgianOfficialJournalFr' =>
				[
					0 => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=fr&btw=0212347945&liste=Liste',
				],
			'websiteBelgianOfficialJournalNl' =>
				[
					0 => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=nl&btw=0212347945&liste=Liste',
				],
			'websiteInfoFr'                   =>
				[
				],
			'websiteInfoNl'                   =>
				[
				],
			'otherAgreementFr'                =>
				[
				],
			'otherAgreementNl'                =>
				[
				],
			'subventionFr'                    =>
				[
				],
			'subventionNl'                    =>
				[
				],
		];
		
		$expected = [
			'id'                                => 13219,
			'legalStatus.id'                    => 932,
			'legalStatus.labelFr'               => 'Service public - CPAS',
			'legalStatus.labelNl'               => 'Openbare dienst - OCMW',
			'isInterpreting'                    => false,
			'address.id'                        => null,
			'address.x'                         => 152302,
			'address.y'                         => 171263,
			'address.lon'                       => 4.401445106123757,
			'address.lat'                       => 50.851756126658735,
			'address.municipalityFr'            => 'SCHAERBEEK',
			'address.municipalityNl'            => 'SCHAARBEEK',
			'address.streetFr'                  => 'Boulevard Auguste Reyers',
			'address.streetNl'                  => 'Auguste Reyerslaan',
			'address.number'                    => '70',
			'address.numberBuilding'            => null,
			'address.zipCode'                   => 1030,
			'address.postalBox'                 => null,
			'address.postalCodeFr'              => '1030 SCHAERBEEK',
			'address.postalCodeNl'              => '1030 SCHAARBEEK',
			'address.districtFr'                => 'REYERS',
			'address.districtNl'                => 'REYERS',
			'address.districtCode'              => '87',
			'address.statisticsCode'            => '21015A77-',
			'address.remarkFr'                  => null,
			'address.remarkNl'                  => null,
			'address.statisticsNameFr'          => 'R.T.B.',
			'address.statisticsNameNl'          => 'B.R.T.',
			'categories'                        => null,
			'sectorsObjects'                    => null,
			'nameOfficialFr'                    => 'COORDINATION DE L\'ACTION SOCIALE DE SCHAERBEEK',
			'nameOfficialNl'                    => 'COÖRDINATIE VAN DE SCHAARBEEKSE SOCIALE ACTIE',
			'descriptionFr'                     => null,
			'descriptionNl'                     => null,
			'activitiesFr'                      => 'Coordination qui regroupe des associations et institutions qui exercent une activité à caractère social ou socio-culturel sur le territoire de la commune de Schaerbeek, afin de soutenir la réflexion commune et l\'action concertée dans la lutte contre la pauvreté et l\'exclusion sociale :
- Rencontres de connaissance du réseau : échange entre travailleurs sociaux d’un quartier ou sur base d’une thématique
- Groupes de travail thématiques autour de projets : Guide (création et suivi du Guide de l\'accompagnant social schaerbeekois), personnes âgées
- Module de présentation du réseau social schaerbeekois
- Journées thématiques, campagnes de sensibilisation, etc.
- Outils de communication : site internet, newsletter, guide/répertoire des services et associations',
			'activitiesNl'                      => 'Coördinatie van verenigingen en instellingen die een sociale of socioculturele activiteit verrichten op het grondgebied van de gemeente Schaarbeek, die nadenkt over de bestrijding van armoede en sociale uitsluiting en actie voert tegen armoede en sociale uitsluiting:
- ontmoetingsmomenten netwerk: uitwisseling tussen sociaal werkers van een wijk of over een thematiek
- thematische werkgroepen rond projecten: Gids (opstellen en opvolgen van de gids voor de sociale begeleiding van de Schaarbekenaars), Ouderen
- presentatiemodule van het sociale netwerk in Schaarbeek
- themadagen, bewustmakingscampagnes, enz.
- communicatietools: website, nieuwsbrief, gids/lijst van de diensten en verenigingen',
			'accessFr.0'                        => 'Par e-mail',
			'accessFr.1'                        => 'Par téléphone',
			'accessNl.0'                        => 'Per e-mail',
			'accessNl.1'                        => 'Telefonisch',
			'permanencyFr'                      => 'Du lundi au vendredi de 8h30 à 16h',
			'permanencyNl'                      => 'Van maandag tot vrijdag van 8.30 uur tot 16 uur',
			'publicFr'                          => 'Toute organisation schaerbeekoise qui se sent concernée par la lutte contre la pauvreté et l\'exclusion sociale',
			'publicNl'                          => 'Schaarbeekse verenigingen die zich aangesproken voelen door de bestrijding van de armoede en de sociale uitsluiting',
			'remarkFr'                          => '- Partenariat entre le CPAS de Schaerbeek et la coordination sociale associative active à Schaerbeek, la Coordination Sociale de Schaerbeek (CSS)',
			'remarkNl'                          => '- Samenwerking tussen het OCMW van Schaarbeek en de sociale coördinatie van verenigingen actief in Schaarbeek, de Sociale Coördinatie van Schaarbeek',
			'pmRp.0'                            => '0212.347.945',
			'langStatus'                        => 'FR-NL',
			'lastUpdate'                        => '28/08/20',
			'startDate'                         => null,
			'type'                              => null,
			'nameAlternativeFr.0'               => 'CASS',
			'nameAlternativeNl.0'               => 'CSSA',
			'telFr.0'                           => '02/435.51.38',
			'telFr.1'                           => '02/435.51.39',
			'telNl.0'                           => '02/435.51.38',
			'telNl.1'                           => '02/435.51.39',
			'emailFr.0'                         => 'cass@cpas-schaerbeek.be',
			'emailNl.0'                         => 'cass@cpas-schaerbeek.be',
			'websiteOfficialFr.0'               => 'http://www.cass-cssa.be',
			'websiteOfficialNl.0'               => 'http://www.cass-cssa.be',
			'websiteBelgianOfficialJournalFr.0' => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=fr&btw=0212347945&liste=Liste',
			'websiteBelgianOfficialJournalNl.0' => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=nl&btw=0212347945&liste=Liste',
		];
		
		
		$actual = Util::flattenAssocArray($data, false);
		
		Assert::assertSame($expected, $actual);
	}
	
	/**
	 *
	 * @covers \AE\Library\CustomField\Util\Util
	 * @since  version
	 */
	public function testFlatten5LevelsDeepAssocArrayReturnsValidData()
	{
		$data = [
			'a0' => [
				'a1' => [
					'a2' => [
						'a3' => [
							'a4' => [
								'a5' => null,
							],
						],
					],
				],
			],
			'b0' => [
				'b1' => [
					'b2' => [
						'b3' => [
							'b4' => [
								'b5' => null,
							],
						],
					],
				],
			],
			'c0' => [
				'c1' => [
					'c2' => [
						'c3' => [
							'c4' => [
								'c5' => null,
							],
						],
					],
				],
			],
		];
		
		$expected = [
			'a0.a1.a2.a3.a4.a5' => null,
			'b0.b1.b2.b3.b4.b5' => null,
			'c0.c1.c2.c3.c4.c5' => null,
		];
		
		$actual = Util::flattenAssocArray($data, false);
		
		Assert::assertSame($expected, $actual);
		
	}
	
	/**
	 *
	 * @covers \AE\Library\CustomField\Util\Util::getJsonArray
	 * @covers \AE\Library\CustomField\Core\Constant::getDataDirectory
	 * @since version
	 */
	public function testGetJsonArrayWithCachedApiJsonFile()
	{
		$filename = Constant::getDataDirectory() . 'api.json';
		$data = file_get_contents($filename);
		
		$expected = [
			'id'                              => 13219,
			'legalStatus'                     =>
				[
					'id'      => 932,
					'labelFr' => 'Service public - CPAS',
					'labelNl' => 'Openbare dienst - OCMW',
				],
			'isInterpreting'                  => false,
			'address'                         =>
				[
					'id'               => null,
					'x'                => 152302,
					'y'                => 171263,
					'lon'              => 4.401445106123757,
					'lat'              => 50.851756126658735,
					'municipalityFr'   => 'SCHAERBEEK',
					'municipalityNl'   => 'SCHAARBEEK',
					'streetFr'         => 'Boulevard Auguste Reyers',
					'streetNl'         => 'Auguste Reyerslaan',
					'number'           => '70',
					'numberBuilding'   => null,
					'zipCode'          => 1030,
					'postalBox'        => null,
					'postalCodeFr'     => '1030 SCHAERBEEK',
					'postalCodeNl'     => '1030 SCHAARBEEK',
					'districtFr'       => 'REYERS',
					'districtNl'       => 'REYERS',
					'districtCode'     => '87',
					'statisticsCode'   => '21015A77-',
					'remarkFr'         => null,
					'remarkNl'         => null,
					'statisticsNameFr' => 'R.T.B.',
					'statisticsNameNl' => 'B.R.T.',
				],
			'categories'                      => null,
			'categoriesObject'                =>
				[
				],
			'sectorsObjects'                  => null,
			'nameOfficialFr'                  => 'COORDINATION DE L\'ACTION SOCIALE DE SCHAERBEEK',
			'nameOfficialNl'                  => 'COÖRDINATIE VAN DE SCHAARBEEKSE SOCIALE ACTIE',
			'descriptionFr'                   => null,
			'descriptionNl'                   => null,
			'activitiesFr'                    => 'Coordination qui regroupe des associations et institutions qui exercent une activité à caractère social ou socio-culturel sur le territoire de la commune de Schaerbeek, afin de soutenir la réflexion commune et l\'action concertée dans la lutte contre la pauvreté et l\'exclusion sociale :
- Rencontres de connaissance du réseau : échange entre travailleurs sociaux d’un quartier ou sur base d’une thématique
- Groupes de travail thématiques autour de projets : Guide (création et suivi du Guide de l\'accompagnant social schaerbeekois), personnes âgées
- Module de présentation du réseau social schaerbeekois
- Journées thématiques, campagnes de sensibilisation, etc.
- Outils de communication : site internet, newsletter, guide/répertoire des services et associations',
			'activitiesNl'                    => 'Coördinatie van verenigingen en instellingen die een sociale of socioculturele activiteit verrichten op het grondgebied van de gemeente Schaarbeek, die nadenkt over de bestrijding van armoede en sociale uitsluiting en actie voert tegen armoede en sociale uitsluiting:
- ontmoetingsmomenten netwerk: uitwisseling tussen sociaal werkers van een wijk of over een thematiek
- thematische werkgroepen rond projecten: Gids (opstellen en opvolgen van de gids voor de sociale begeleiding van de Schaarbekenaars), Ouderen
- presentatiemodule van het sociale netwerk in Schaarbeek
- themadagen, bewustmakingscampagnes, enz.
- communicatietools: website, nieuwsbrief, gids/lijst van de diensten en verenigingen',
			'accessFr'                        =>
				[
					0 => 'Par e-mail',
					1 => 'Par téléphone',
				],
			'accessNl'                        =>
				[
					0 => 'Per e-mail',
					1 => 'Telefonisch',
				],
			'permanencyFr'                    => 'Du lundi au vendredi de 8h30 à 16h',
			'permanencyNl'                    => 'Van maandag tot vrijdag van 8.30 uur tot 16 uur',
			'publicFr'                        => 'Toute organisation schaerbeekoise qui se sent concernée par la lutte contre la pauvreté et l\'exclusion sociale',
			'publicNl'                        => 'Schaarbeekse verenigingen die zich aangesproken voelen door de bestrijding van de armoede en de sociale uitsluiting',
			'publicationFr'                   =>
				[
				],
			'publicationNl'                   =>
				[
				],
			'remarkFr'                        => '- Partenariat entre le CPAS de Schaerbeek et la coordination sociale associative active à Schaerbeek, la Coordination Sociale de Schaerbeek (CSS)',
			'remarkNl'                        => '- Samenwerking tussen het OCMW van Schaarbeek en de sociale coördinatie van verenigingen actief in Schaarbeek, de Sociale Coördinatie van Schaarbeek',
			'pmRp'                            =>
				[
					0 => '0212.347.945',
				],
			'langStatus'                      => 'FR-NL',
			'lastUpdate'                      => '28/08/20',
			'startDate'                       => null,
			'type'                            => null,
			'nameAlternativeFr'               =>
				[
					0 => 'CASS',
				],
			'nameAlternativeNl'               =>
				[
					0 => 'CSSA',
				],
			'nameFormerFr'                    =>
				[
				],
			'nameFormerNl'                    =>
				[
				],
			'nameServiceFr'                   =>
				[
				],
			'nameServiceNl'                   =>
				[
				],
			'telFr'                           =>
				[
					0 => '02/435.51.38',
					1 => '02/435.51.39',
				],
			'telNl'                           =>
				[
					0 => '02/435.51.38',
					1 => '02/435.51.39',
				],
			'emailFr'                         =>
				[
					0 => 'cass@cpas-schaerbeek.be',
				],
			'emailNl'                         =>
				[
					0 => 'cass@cpas-schaerbeek.be',
				],
			'faxFr'                           =>
				[
				],
			'faxNl'                           =>
				[
				],
			'websiteOfficialFr'               =>
				[
					0 => 'http://www.cass-cssa.be',
				],
			'websiteOfficialNl'               =>
				[
					0 => 'http://www.cass-cssa.be',
				],
			'websiteUnofficialFr'             =>
				[
				],
			'websiteUnofficialNl'             =>
				[
				],
			'websiteBelgianOfficialJournalFr' =>
				[
					0 => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=fr&btw=0212347945&liste=Liste',
				],
			'websiteBelgianOfficialJournalNl' =>
				[
					0 => 'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=nl&btw=0212347945&liste=Liste',
				],
			'websiteInfoFr'                   =>
				[
				],
			'websiteInfoNl'                   =>
				[
				],
			'otherAgreementFr'                =>
				[
				],
			'otherAgreementNl'                =>
				[
				],
			'subventionFr'                    =>
				[
				],
			'subventionNl'                    =>
				[
				],
		];
		
		$actual = Util::getJsonArray($data);
		
		Assert::assertSame($expected, $actual);
	}
	
}
