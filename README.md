# Choco Fields

![Banner](./banner.svg)

A plugin allowing to populate Joomla Custom Fields from Web Services

## Preamble

Please go <https://sync.joomlacustomfields.org/fr/> for more explanations and for a demo.

In this plugin, we retrieve information from <https://social.brussels>, which is a Directory of social organizations in Brussels.

Example of page for a given organization: <https://social.brussels/organisation/470>

Corresponding page in json format (which will then be used to synchronize our Custom Field values): <https://social.brussels/rest/organisation/470>

## INSTALLATION
 * Go to build folder and install latest extension file


## Setup of the website for this plugin

This version of the plugin uses what we call "Dynamic Custom Field Inference" . Behind this fancy looking words are basically if statements and some "secret" public sauce since this repo is public anyone can see it and play around with the code to grasp the idea behind this seemingly "complex" concept.

This plugin tries it's best to infer and create dynamically the types of custom fields that migth come from the cached fetched json api.

It attempts to adapt to any kind of json api regardless of its structure.

It accomplishes this by "flattening" the resulting cached json using the native Joomla! Registry flatten method which turns any json to a one dimension associative array which deeper keys are merge in one first level key seperated by a custom separator in our example it's a dot.

Joomla natively supports multilingual websites. So we assign the corresponding language (FR / NL) to each Custom Field, meaning that they will appear in the front-end in function of the selected language on the website.

## Options

The plugin has several Options. You can indeed:
- Type a default api url in plugin params as fallback to each url customisable in the each article
- Type a default resource id for the same purpose. (as a fallback)

- Use the native Joomla! Ajax feature special url to call trigger a manual update via url, cron or webcron
- enable/disable the Action Log (you can access the Log file on JPATH_ROOT/administrator/logs/chocofields.trace.log.php or JPATH_ROOT/logs)

## SPECIAL THANKS
This plugin is based on the work of a core "team" of 4 joomlers which are:

* [Pascal Leconte](https://www.conseilgouz.com)
* [Christophe Avonture](https://avonture.be)
* [Marc Dechèvre](https://woluweb.be)
* [Alexandre ELISÉ](https://alexandre-elise.fr)


## BASED ON
The first version of this plugin can be found [here](https://github.com/woluweb/updatecf)

## CONTRIBUTIONS
Contributions are welcome to improve this plugin. Still work in progress.

## COMMUNITY

In English:

> Get in touch on social media or contact me directly

* Website: https://alexandre-elise.fr/en
* Contact: https://alexandre-elise.fr/en/say-hello
* Newsletter: https://alexandre-elise.fr/en/get-newsletter

---------------------------------------------------

En français

> Contactez-moi directement ou bien sur les réseaux sociaux

* Site web: https://alexandre-elise.fr
* Contact: https://alexandre-elise.fr/contact
* Newsletter: https://alexandre-elise.fr/newsletter

-------------------------------------------------------

* Twitter: https://twitter.com/mralexandrelise
* Facebook: https://www.facebook.com/mralexandrelise
* Linkedin: https://www.linkedin.com/in/alexandree
* Youtube: coderparlerpartager
