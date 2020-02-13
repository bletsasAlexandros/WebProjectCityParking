# WebProjectCityParking
Project for University of Patras-> City Parking in Thessaloniki

In this project we create a website that can map every block in a city given an kml file whith the coordinates and the population of each block.
The blocks start as grey colors and the admin can update the parking spaces for each one
Admin also based on statistics updates the demand of parking each block depending of the hour
If many people request parking space in a block then the block becomes red
If there is over a 50% end less than 80% of demand it becomes yellow
else it becomes green
Now the user can select the hour and the place he wants to park
dbscan algorithm finds the best possible parking spot for the user and place a marker to the map with the reccomendation
