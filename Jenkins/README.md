# Jenkins
Steps to create a Jenkins job for drupalci

![Jenkins job](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/0-jenkins_job.jpg "Jenkins Job")

Create a new Freestyle job on Jenkins and add the following arguments

1. Field project. This is the project name in drupal.org i.e. adstxt

![Field Project](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/1-project.jpg "Field Project")

1. Field version. This is the version of the module, using the same syntax as Composer i.e. `1.0.0` or `^1.0` or `dev-1.x`

![Field Version](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/2-version.jpg "Field Version")

1. Field Drupal Version. This is a dropdown with the major Drupal versions i.e. 7/8/9. More specific versions can be used too.

![Field Drupal Version](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/3-drupal_version.jpg "Field Drupal Version")

1. Field VCS. The repo url in case you are using a fork. i.e. `https://git.drupalcode.org/project/adstxt.git`

![Field VCS](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/4-vcs.jpg "Field VCS")

1. Field Patches. A list of patches to apply. i.e. `https://www.drupal.org/files/issues/2020-10-14/adstxt_app_ads.patch`

![Field Patches](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/5-patches.jpg "Field Patches")

1. Field Dependencies. A list of project dependencies. i.e. `drupal/devel`

![Field Dependencies](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/6-dependencies.jpg "Field Dependencies")

1. Build step: Shell command. The script to be called with the arguments above.

![Script](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/7-scripts.jpg "Script")

```
[ ! -z "${VCS}" ] && VCS="--vcs ${VCS}"
[ ! -z "${PATCHES}" ] && PATCHES="--patches ${PATCHES}"
[ ! -z "${DEPENDENCIES}" ] && DEPENDENCIES="--dependencies ${DEPENDENCIES}"

docker pull marcelovani/drupalci:${DRUPAL}-apache
docker run -v ${PWD}/artifacts:/artifacts --name drupalci --rm marcelovani/drupalci:${DRUPAL}-apache \
--project ${PROJECT} \
--version ${VERSION} \
${VCS} ${PATCHES} ${DEPENDENCIES}
```

1. Post build actions: Artifact. This makes Jenkins store the artifacts

![Artifacts](https://github.com/marcelovani/drupalci/blob/master/Jenkins/images/8-artifacts.jpg "Artifacts")
