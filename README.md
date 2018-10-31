# Additional Notes
This is the original Wordpress plugin with a few cosmetic changes.

--dan-el

# EsoSets Wordpress

Welcome to the eso-sets plugin for Wordpress. With this plugin you can show tooltips in Wordpress with gear set information about the Elder Scrolls Online. All data is directly pulled from https://eso-sets.com and https://eso-skillbook.com

## Sets
In order to use this plugin you must use shortcodes in your Wordpress posts and pages. You need the set id of a specific set. Lets say you want to include the Death's Wind set, which has id 1. You would use the following shortcode.

```[esoset id="1"]```

Note that some Wordpress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

```[esoset id="1" tooltip="true"]```

## Skills
In order to use this plugin you must use shortcodes in your Wordpress posts and pages. You need the set id of a specific skill. Lets say you want to include the Dragonknight Standard skill, which has id 8. You would use the following shortcode.

```[esoskill id="8"]```

Note that some Wordpress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

```[esoskill id="8" tooltip="true"]```


## Skill bars
In order to use this plugin you must use shortcodes in your Wordpress posts and pages. You need the skill id of all the skills on the bar. Lets say you want to include the Dragonknight Standard skill and fill a skillbar with it, which has id 8. You would use the following shortcode.

```[esoskillbar skill_1="8" skill_2="8" skill_3="8" skill_4="8" skill_5="8" skill_ult="8"]```

Note that some Wordpress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

```[esoskillbar tooltip="true" skill_1="8" skill_2="8" skill_3="8" skill_4="8" skill_5="8" skill_ult="8"]```

Please do not access the eso-sets api with a modified version of this plugin. Accessing the API with a modified version of this plugin may result in the permanent suspension of your domain from the eso-sets api.
