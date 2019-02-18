# PeopleGrid
UI element originally designed for displaying a grid of alumni profiles designed for [UTD's alumni website.](https://alumni.utdallas.edu/) This is a general-use plugin to display a responsive grid of people using flexbox in Wordpress.

## Screenshot

![Screenshot of grid](screenshots/screenshot.PNG)

## Setup

This plugin's only dependency is [Advanced Custom Fields.](https://www.advancedcustomfields.com/) Once you have installed that, add the `PeopleGrid` folder to your `plugins` folder in your Wordpress installation. Then, activate the plugin in your Wordpress admin panel. This plugin sets up two things:

1. A Custom Post type called "People" (singular "Person") to store the people you want to display on your grid.
2. A field group (using Advanced Custom Fields) for adding a new person to the list.

Each person has a thumbnail image, a title, and a position. The thumbnail image should be a profile picture of the person, the title is their name, and the position is the subheader displayed under their name. Once you have added people in wordpress, you can display the grid on any page using the `[people_grid]` shortcode.
