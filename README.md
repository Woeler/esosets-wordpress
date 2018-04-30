# EsoSets Wordpress

Welcome to the eso-sets plugin for Wordpress. With this plugin you can show tooltips in Wordpress with gear set information about the Elder Scrolls Online. All data is directly pulled from https://eso-sets.com

In order to use this plugin you must use shortcodes in your Wordpress posts and pages. You need the set id of a specific set. Lets say you want to include the Death's Wind set, which has id 1. You would use the following shortcode.

```[esoset id="1"]```

Note that some Wordpress themes may need some help recognizing this is a tooltip. If the above code does not work, try the following.

```[esoset id="1" tooltip="true"]```

Please do not access the eso-sets api with a modified version of this plugin. Accessing the API with a modified version of this plugin may result in the permanent suspension of your domain from the eso-sets api.