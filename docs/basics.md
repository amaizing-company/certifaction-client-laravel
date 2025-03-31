# Basics

The package is based on a client api, which was developed for laravel. This allows you to use the package in different 
ways:

1. with the predefined workflow via the database models, jobs, events and commands.
2. with your own workflow directly via the API classes.

The documentation mainly deals with the first use case with the pre-defined workflows, as this can also explain the use 
of the API classes.

## Jobs and Events

The package relies on jobs to execute various actions. Most jobs trigger several events. Based on these events, you 
can customize the workflow to the needs of your application. You can find an explanation of the existing events 
in [Events](events.md)

## Model-based configuration

Since the package supports multiple user or document models. The configuration for the model-dependent methods can 
be carried out directly in the models. You can find out more about the configuration and extension options 
under [Models](models.md).

