# Overview
## Origin

This dashbord come from :  
https://github.com/liaralabs/quickbox_dashboard  (a fork of the one made by the quickbox team)  
It's/Was used in the swizzin seedbox solution :  
https://github.com/liaralabs/swizzin

## Description

This is a web panel displaying some useful informations about your seedbox environment,   
including net data, up time, disk usage, cpu usage etc..  
It also include a service control center and links for your seedbox apps.

## Goal

Having the same or even more functionalities  
with less code, overall better maintainability and ease of menus/services addition.

---
# Actual state
## Code size

<table>
  <tr>
    <th scope="col">Language</th>
    <th scope="col">Original</th>
    <th scope="col">Now</th>
  </tr>
  <tr>
    <td>PHP and JS</td>
    <td>3509 K</td>
    <td>2289 K</td>
  </tr>
  <tr>
    <td>JS </td>
    <td>3089 K</td>
    <td>2097 K</td>
  </tr>
  <tr>
    <td>PHP </td>
    <td>420 K</td>
    <td>192 K</td>
  </tr>
</table>

Overall code size reduced by `33.51%` (php and js)  
![Overall code reduction progress bar](https://github.com/Lukylix/Repos_Images/raw/master/quickbox_dashboard_rework/progress_php_js.png)

PHP code size reduced by `44.76%`  
![Overall code reduction progress bar](https://github.com/Lukylix/Repos_Images/raw/master/quickbox_dashboard_rework/progress_php.png)

## Sreenshot

![Panel screenshot](https://github.com/Lukylix/Repos_Images/raw/master/quickbox_dashboard_rework/full_panel.png)

---

## Random infos
### Command used to get file size:
```bash
ls -s --block-size=1K $(find . -type f | egrep "*\.(php|js)$") | cut -d '.' -f 1 | paste -sd+ - | bc
```
In order we got :  
1. Get a list of all php or js files
    ```bash
    find . -type f | egrep "*\.(php|js)$"
    ```
2. Get the size in K of those files
    ```bash
    ls -s --block-size=1K $(...)
    ```
3. Remove files name from the list
    ```bash
    cut -d '.' -f 1 
    ```
4. Chain all numbers with '+' as separator
    ```bash
    paste -sd+ -
    ```
5. Execute the string (add all numbers)
